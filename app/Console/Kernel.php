<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Console;

use App\Jobs\GenerateSitemap;
use App\Console\Commands\CreateAdminUser;
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
        CreateAdminUser::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new GenerateSitemap())->daily();
        // $schedule->command('telescope:prune --hours=24')->daily()->withoutOverlapping();
        $schedule->command('medialibrary:clean --force')->dailyAt('4:30')->runInBackground();
        $schedule->command('medialibrary:regenerate --only-missing --force')->dailyAt('5:00')->runInBackground();
        $schedule->command('horizon:snapshot')->everyFiveMinutes()->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
