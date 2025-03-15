<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        return Inertia::render('Posts/Index', [
            'posts' => Post::with('user')
                ->withTrashed()
                ->paginate(10)
                ->through(fn($post) => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'description' => $post->description,
                    'user' => $post->user ? [
                        'name' => $post->user->name,
                        'email' => $post->user->email,
                    ] : null,
                    'formatted_date' => $post->created_at->format('d M Y'),
                    'deleted_at' => $post->deleted_at
                ])
        ]);
    }

    public function show($id)
    {
        $post = Post::with('user', 'comments')->findOrFail($id);
        if (request()->wantsJson()) {
            return response()->json([
                'id' => $post->id,
                'title' => $post->title,
                'description' => $post->description,
                'user' => $post->user ? [
                    'name' => $post->user->name,
                    'email' => $post->user->email,
                ] : null,
                'comments' => $post->comments,
            ]);
        }
        return Inertia::render('Posts/Show', [
            'post' => $post
        ]);
    }

    public function create()
    {
        return Inertia::render('Posts/Create', [
            'users' => User::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        Post::create($validated);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function edit(Post $post)
    {
        return Inertia::render('Posts/Edit', [
            'post' => $post,
            'users' => User::all()
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $post->update($validated);

        return to_route('posts.index')
            ->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully.',
            'deleted_at' => $post->deleted_at
        ]);
    }

    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();

        return response()->json([
            'message' => 'Post restored successfully!',
            'restored_at' => now()
        ]);
    }
}
