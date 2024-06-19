<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|min:3|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        try
        {
            $user = User::create([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => $fields['password']
            ]);
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            $response = [
                'user' => [
                    'name' => $user->name, 
                    'id' => $user->id, 
                    'email' => $user->email
                ],
                'token' => $token,
                'message' => "Welcome to RylTechDocs! Let's simplify your documentation journey!"
            ];

            DB::commit();
    
            return response($response, 201);
        }
        catch(Exception $e)
        {
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()],400);
        }
        
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Incorrect email or password!'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'user' => [
                'name' => $user->name, 
                'id' => $user->id, 
                'email' => $user->email],
            'token' => $token,
            'message' => 'Welcome back, ' . ucwords($user->name) . '!'
        ];

        return response($response, 201);
    }

    public function updateProfile(Request $request) {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $userId = auth()->user()->id;

        $validatedData = $request->validate([
            'name' => 'required|min:3|max:255|string',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
        ]);

        try {
            $user = User::findOrFail($userId);
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->save();

            return response()->json(['message' => "You've successfully updated your profile"], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured: ', $e->getMessage()], 500);
        }

    }

    public function updatePassword(Request $request) {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $userId = auth()->user()->id;
        $validatedPassword = $request->validate([
            'password' => 'sometimes|string|min:6|confirmed'
        ]);

        try {
            $user = User::findOrFail($userId);
            $user->password = $validatedPassword['password'];
            $user->save();

            return response()->json(['message' => "You've successfully changed your password"], 200);
        } catch(\Exception $e) {
            return response()->json(['message' => 'An error occured: ' . $e], 500);
        }
    }
}
