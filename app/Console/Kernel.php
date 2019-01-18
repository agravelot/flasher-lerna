<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Console;

use App\Console\Commands\CreateAdminUser;
use App\Jobs\Backup;
use App\Jobs\BackupClean;
use App\Jobs\BackupMonitor;
use App\Jobs\GenerateSitemap;
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
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new GenerateSitemap())->daily()->withoutOverlapping();
        $schedule->job(new BackupClean())->daily()->at('01:00')->withoutOverlapping();
        $schedule->job(new Backup())->daily()->at('02:00')->withoutOverlapping();
        $schedule->job(new BackupMonitor())->daily()->at('03:00')->withoutOverlapping();
        $schedule->command('telescope:prune --hours=48')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
