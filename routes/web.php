<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::resource('posts', PostController::class);

Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');

// Restore Route (not part of resource)
Route::patch('/posts/{post}/restore', [PostController::class, 'restore'])->name('posts.restore');

// Comments Routes
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
