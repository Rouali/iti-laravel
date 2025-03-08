<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = [
            ['id' => 1, 'title' => 'laravel', 'posted_by' => 'rawan', 'created_at' => '2025-03-08 12:47:00'],
            ['id' => 2, 'title' => 'HTML', 'posted_by' => 'ali', 'created_at' => '2025-04-10 11:00:00'],
        ];

        return view('posts.index',['posts' => $posts]);
    }

    public function show($id)
    {
        $post = [
            'id' => 1, 
            'title' => 'laravel',
            'description' => 'some description',
            'posted_by' => [
                'name' => 'rawan',
                'email' => 'rawan@gmail.com',
                'created_at' => 'Thursday 25th of December 1975 02:15:16 PM'
            ],
            'created_at' => '2025-03-08 12:47:00',
        ];
        return view('posts.show', ['post' => $post]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store()
    {
        $title = request()->title;
        $description = request()->description;
        return to_route('posts.show', 1);
        // return to_route('posts.index');
    }
    public function edit($id){
        return view('posts.edit');
    }

    public function update($id){
        return to_route('posts.show',1);
    }
}