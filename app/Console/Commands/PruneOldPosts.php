<?php

namespace App\Console\Commands;

use App\Jobs\PruneOldPostsJob;
use Illuminate\Console\Command;

class PruneOldPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune posts that are older than 2 years';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dispatching PruneOldPostsJob...');
        PruneOldPostsJob::dispatch();
        $this->info('Job dispatched successfully!');
    }
}