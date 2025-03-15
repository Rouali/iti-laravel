<?php

namespace App\Http\Controllers\Api\PostController;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::with('user')->withTrashed()->latest()->paginate(10);
        return PostResource::collection($posts);
    }

    public function show($id)
    {
        $post = Post::with(['user', 'comments'])->findOrFail($id);
        return new PostResource($post);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'required|string|min:10',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
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
            return new PostResource($post);
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create post.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'required|string|min:10',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
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
            return new PostResource($post);
        } catch (\Exception $e) {
            Log::error('Error updating post: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update post.'], 500);
        }
    }

    public function destroy(Post $post)
    {
        try {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Error deleting post: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete post.'], 500);
        }
    }

    public function restore($id)
    {
        try {
            $post = Post::withTrashed()->findOrFail($id);
            $post->restore();
            return response()->json(['message' => 'Post restored successfully!', 'restored_at' => now()]);
        } catch (\Exception $e) {
            Log::error('Error restoring post: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to restore post.'], 500);
        }
    }
}
