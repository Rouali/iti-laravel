<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use App\Models\Post;

class PostResponse implements Responsable
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function toResponse($request)
    {
        return response()->json([
            'id' => $this->post->id,
            'title' => $this->post->title,
            'description' => $this->post->description,
            'user' => $this->post->user ? [
                'name' => $this->post->user->name,
                'email' => $this->post->user->email,
            ] : null,
            'deleted_at' => $this->post->deleted_at,
        ]);
    }
}
