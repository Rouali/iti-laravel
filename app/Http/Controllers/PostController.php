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
                'image' => 'nullable|image|max:2048', // Ensure this validates an image
            ]);
            
            // Create a new post
            $post = new Post();
            $post->title = $validated['title'];
            $post->description = $validated['description'];
            $post->user_id = $validated['user_id']; // Make sure to set the user_id
            $post->slug = Str::slug($validated['title']);
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $post->image = $imagePath;
            }
            
            // Save the post
            $post->save();
            
            return redirect()->route('posts.index')->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error creating post: ' . $e->getMessage());
            
            // Return a response with the error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            // Find the existing post
            $post = Post::findOrFail($id);

            // Validate incoming request data
            $validated = $request->validate([
                'title' => 'required|string|min:3',
                'description' => 'required|string|min:10',
                'user_id' => 'required|exists:users,id',
                'image' => 'nullable|image|max:2048', // Validate image: optional, must be a valid image, max size 2MB
            ]);

            // Update the post's title, description, and slug
            $post->title = $validated['title'];
            $post->description = $validated['description'];
            $post->user_id = $validated['user_id']; // Make sure user_id is updated
            $post->slug = Str::slug($validated['title']);

            // Handle image upload: delete old image if a new one is uploaded
            if ($request->hasFile('image')) {
                // Delete the old image if one exists
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }

                // Store the new image and get its path
                $imagePath = $request->file('image')->store('images', 'public');
                $post->image = $imagePath;
            }

            // Save updated post
            $post->save();

            return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating post: ' . $e->getMessage());
            
            // Return a response with the error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function destroy(Post $post)
    {
        try {
            // Remove the old image before deleting the post
            $post->deleteOldImage();
            
            $post->delete();
            
            return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error deleting post: ' . $e->getMessage());
            
            // Return a response with the error
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
            // Log the error for debugging
            Log::error('Error restoring post: ' . $e->getMessage());
            
            // Return a response with the error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

