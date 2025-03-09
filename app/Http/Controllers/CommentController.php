<?php
namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $post->comments()->create([
            'body' => $request->body,
        ]);        

        return back();
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back();
    }
}
