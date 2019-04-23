<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Console;

use App\Console\Commands\CreateAdminUser;
use App\Jobs\Backup;
use App\Jobs\BackupClean;
use App\Jobs\BackupMonitor;
use App\Jobs\GenerateSitemap;
use App\Jobs\ProcessBackup;
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
        $schedule->job(
            ProcessBackup::withChain([
                new Backup(),
                new BackupClean(),
                new BackupMonitor(),
            ])->dispatch()->allOnQueue('backup')
        )->daily()->at('02:00')->withoutOverlapping();
        $schedule->command('telescope:prune --hours=24')->daily()->withoutOverlapping();
        $schedule->command('medialibrary:regenerate --only-missing --force')->hourly()->withoutOverlapping();
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
