<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documentation;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentationResource;
use App\Http\Requests\DocumentationRequest;
use App\Http\Resources\DocumentationCollection;

class DocumentationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = $request->input('query');
            $pageSize = $request->input('pageSize', 6);

            if ($query) {
                $documentations = Documentation::where('title', 'like', '%' . $query . '%')
                                                // ->orWhere('author', 'like', '%' . $query . '%')
                                                ->orWhereRaw("author LIKE '%$query%'")
                                                ->orWhereRaw("JSON_EXTRACT(content,'$[*].text') LIKE '%$query%'")
                                                ->orWhereRaw("JSON_EXTRACT(content,'$[*].code') LIKE '%$query%'")
                                                ->orWhere('description', 'like', '%' . $query . '%')
                                                ->orWhere('tags', 'like', '%' . $query . '%')
                                                ->paginate($pageSize);
            } else {
                $documentations = Documentation::paginate($pageSize);
            }
    
            return new DocumentationCollection($documentations);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display documentations for a specific user.
     */
    public function showByUserId(string $userId) {
        $pageSize = 5;
        $documentations = Documentation::where('user_id', $userId)->paginate($pageSize);
        return response()->json($documentations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocumentationRequest $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;
            Documentation::create($validatedData);  
            return response()->json(['message' => 'New documentation has been successfully created.'], 201);         
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured: ' . $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource by id.
     */
    public function show(string $id)
    {
        try {
            $documentation = Documentation::findOrFail($id);
            $documentation->content = json_decode($documentation->content);
            return new DocumentationResource($documentation);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured: ' .  $e->getMessage()], 500);
        }        
    }

    /**
     * Display the specified resource by slug.
     */
    public function showBySlug(string $slug) {
        try {
            $documentation = Documentation::where('slug', $slug)->firstOrFail();
            $documentation->content = json_decode($documentation->content);
            return new DocumentationResource($documentation);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DocumentationRequest $request, string $id)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        try {
            $documentation = Documentation::findOrFail($id);

            if (auth()->user()->id !== $documentation->user_id) {
                return response()->json(['message' => 'This action is not allowed!'], 403);
            }

            $validatedData = $request->validated();
            $documentation->update($validatedData);
    
            return response()->json(['message' => 'Documentation has been successfully updated.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $userId = auth()->user()->id;

        try {
            $documentation = Documentation::findOrFail($id);

            if ($userId !== $documentation->user_id) {
                return response()->json(['message' => 'This action is not allowed!']);
            }

            $documentation->delete();
            return response()->json(['message' => 'Documentation has been deleted!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured: ' . $e->getMessage()], 500);
        }
    }
}
