<?php

namespace App\Console;

use App\Jobs\PruneOldPostsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule the PruneOldPostsJob to run daily at midnight
        // $schedule->job(new PruneOldPostsJob)->dailyAt('00:00');
        $schedule->job(new PruneOldPostsJob)->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}