<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Console;

use App\Jobs\Backup;
use App\Jobs\BackupClean;
use App\Jobs\BackupMonitor;
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
     * @param Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new GenerateSitemap())->daily()->withoutOverlapping();
        $schedule->command('medialibrary:clean --force')->weekly()->at('00:01');
        $schedule->job(new BackupClean())->daily()->at('01:00');
        $schedule->job(new Backup())->withoutOverlapping()->daily()->at('02:00');
        $schedule->job(new BackupMonitor())->daily()->at('04:00');
        //$schedule->command('telescope:prune --hours=24')->daily()->withoutOverlapping();
        $schedule->command('medialibrary:regenerate --only-missing --force')->withoutOverlapping()
            ->daily()->at('05:00');
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
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
