<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('stats:getposts')->everyFiveMinutes();
        $schedule->command('stats:get --from="48 hours ago" --to="now" --type=live')->everyTenMinutes();
        $schedule->command('stats:get --from="48 hours ago" --to="now" --type=delayed')->everyTenMinutes();
        $schedule->command('stats:updateaverages')->everyTenMinutes();
        $schedule->command('stats:emailstats')->dailyAt('09:40')->timezone('Europe/London');

        $schedule->command('stats:getcomments')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
