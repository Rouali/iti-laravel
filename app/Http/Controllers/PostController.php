<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
                    'slug' => $post->slug,
                    'description' => $post->description,
                    'user' => $post->user ? [
                        'name' => $post->user->name,
                        'email' => $post->user->email,
                    ] : null,
                    'formatted_date' => $post->created_at->format('d M Y'),
                    'deleted_at' => $post->deleted_at,
                    'image_url' => $post->getImageUrlAttribute(),
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
                'slug' => $post->slug,
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
            'users' => User::all(),
            'csrf_token' => csrf_token(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'title' => 'required|string|min:3',
                'description' => 'required|string|min:10',
                'user_id' => 'required|exists:users,id',
                'image' => 'nullable|image|max:2048',
            ]);
            
            $post = new Post();
            $post->title = $validated['title'];
            $post->description = $validated['description'];
            $post->user_id = $validated['user_id'];
            $post->slug = Str::slug($validated['title']);
            
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $post->image = $imagePath;
            }
            
            $post->save();
            
            return redirect()->route('posts.index')->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);
            $validated = $request->validate([
                'title' => 'required|string|min:3',
                'description' => 'required|string|min:10',
                'user_id' => 'required|exists:users,id',
                'image' => 'nullable|image|max:2048',
            ]);
            $post->title = $validated['title'];
            $post->description = $validated['description'];
            $post->user_id = $validated['user_id'];
            $post->slug = Str::slug($validated['title']);

            if ($request->hasFile('image')) {
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                $imagePath = $request->file('image')->store('images', 'public');
                $post->image = $imagePath;
            }

            $post->save();

            return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating post: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function destroy(Post $post)
    {
        try {
            $post->deleteOldImage();
            
            $post->delete();
            
            return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting post: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function edit(Post $post)
    {
        return Inertia::render('Posts/Edit', [
            'post' => $post,
            'users' => User::all()
        ]);
    }

    public function restore($id)
    {
        try {
            $post = Post::withTrashed()->findOrFail($id);
            $post->restore();

            return response()->json([
                'message' => 'Post restored successfully!',
                'restored_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Error restoring post: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
