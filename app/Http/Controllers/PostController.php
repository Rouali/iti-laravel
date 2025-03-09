<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(10);
        return view('posts.index', compact('posts'));

        // return view('posts.index',['posts' => $posts]);
    }
    public function create()
    {
        $users = User::all();

        return view('posts.create', ['users' => $users]);
    }
    public function show($id)
    {
        $post = Post::find($id);
    
        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        }
    
        return view('posts.show', ['post' => $post]);
    }
    
    public function edit(Post $post)
    {
    $users = User::all();
    return view('posts.edit', compact('post', 'users'));
    }

    
    public function update(Request $request, Post $post)
    {
        // dd($request->all());
    $post->update([
        'title' => $request->title,
        'description' => $request->description,
        'user_id' => $request->user_id,
    ]);

    return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    public function store(Request $request){
    Post::create([
        'title' => $request->title,
        'description' => $request->description,
        'user_id' => $request->user_id, 
    ]);
    // return to_route('posts.show', $post->id);
    // return to_route('posts.index');
    return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        }
    
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
    

}