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
        // $schedule->command('inspire')->hourly();

        $schedule->command('transaction:verify')->everyFiveMinutes();

        $schedule->command('queue:work database --stop-when-empty')->everyFiveMinutes();

        $schedule->command('queue:retry all')->daily()->at('00:00');

        $schedule->command('backup:clean')->daily()->at('1:00');

        $schedule->command('backup:run --only-db')->daily()->at('01:10');

        $schedule->command('backup:monitor')->daily()->at('01:20');

        $schedule->command('reseller:remind 3')->daily()->at('02:00');

        $schedule->command('user:remindFund 5')->weekly(6, '03:00');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}