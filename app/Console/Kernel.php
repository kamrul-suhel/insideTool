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
        $schedule->command('stats:getposts')->everyMinute();
        $schedule->command('stats:get --from="48 hours ago" --to="now" --type=live')->everyMinute();
        $schedule->command('stats:get --from="48 hours ago" --to="now" --type=delayed')->everyFiveMinutes();
        $schedule->command('stats:updateaverages')->everyThirtyMinutes();
        $schedule->command('stats:emailstats')->daily();
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
