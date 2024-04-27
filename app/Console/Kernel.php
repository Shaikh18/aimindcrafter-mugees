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
        'App\Console\Commands\SubscriptionCheckTaskCommand',
        'App\Console\Commands\StorageClearTaskCommand',
        'App\Console\Commands\DocumentClearTaskCommand',
        'App\Console\Commands\RenewCreditsTaskCommand',
        'App\Console\Commands\YookassaSubscriptionTaskCommand',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('subscription:check')->daily();
        $schedule->command('subscription:renew')->daily();
        $schedule->command('yookassa:check')->daily();
        $schedule->command('document:clear')->everyMinute();
        $schedule->command('storage:clear')->everyMinute();
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
