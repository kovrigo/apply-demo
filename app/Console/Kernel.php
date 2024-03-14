<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Nova\Trix\PruneStaleAttachments;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \CalosKao\MigrateSpecific::class,
        Commands\JobUnpublisher::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Telescope
        $schedule->command('telescope:prune --hours=48')->daily();
        $schedule->call(function () {
            (new PruneStaleAttachments)();
        })->daily();
        // Marketing tasks
        $schedule->command('marketing:schedule')->everyTenMinutes();
        $schedule->command('job:unpublish')->everyMinute();
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
