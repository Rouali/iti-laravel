<?php

namespace App\Jobs;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PruneOldPostsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $twoYearsAgo = Carbon::now()->subYears(2);

        $oldPosts = Post::where('created_at', '<', $twoYearsAgo)->get();

        $count = $oldPosts->count();
        Log::info("PruneOldPostsJob: Found {$count} posts older than 2 years to delete");
        
        foreach ($oldPosts as $post) {
            $post->deleteOldImage();

            $post->delete();
            
            Log::info("PruneOldPostsJob: Deleted post ID {$post->id}: {$post->title}");
        }
        
        Log::info("PruneOldPostsJob: Successfully deleted {$count} old posts");
    }
}

