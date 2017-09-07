<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
         Commands\EntrustUsers::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //create a scheduled job to update sitemap.xml daily
        $schedule->call(function () {
            $content = file_get_contents(url('sitemap/create'));
            if ($content == 'SUCCESS') {
                Log::info("Sitemap successfully updated!");
            } else {
                Log::error("Sitemap successfully updated!");
            }
        })->twiceDaily(1, 13);
    }
}
