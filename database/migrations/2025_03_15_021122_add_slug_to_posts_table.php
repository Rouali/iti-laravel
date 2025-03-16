<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Post;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Post::all()->each(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
                $post->save();
            }
        });
    }

    public function down()
    {

    }
};
