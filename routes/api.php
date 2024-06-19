<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentationController;
use App\Models\Documentation;

Route::apiResource('documentations', DocumentationController::class)->only(['index', 'show']);

Route::get('/documentations/slug/{slug}', [DocumentationController::class, 'showBySlug']);

Route::get('/documentations/user/{userId}', [DocumentationController::class, 'showByUserId']);

// User
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:3,1');

// Private APIs
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('documentations', DocumentationController::class)->except(['index', 'show']);

    Route::put('/users/update', [AuthController::class, 'updateProfile']);
    Route::put('/users/change-password', [AuthController::class, 'updatePassword']);

    // Route::get('/user', function (Request $request) {
    //     return $request->user();
    // });
});