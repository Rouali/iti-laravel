<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

// Public API Routes
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);

// Protected API Routes (Require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    Route::patch('/posts/{post}/restore', [PostController::class, 'restore']);

    // Comments API
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // Authenticated User Data
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
