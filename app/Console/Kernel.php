<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\CreateAdminUser;
use App\Jobs\GenerateSitemap;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array<string>
     */
    protected $commands = [
        CreateAdminUser::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new GenerateSitemap())->daily();
        // $schedule->command('telescope:prune --hours=24')
        //  ->daily()->withoutOverlapping();
        $schedule->command('media-library:clean --force')
            ->dailyAt('4:30')->runInBackground();
        // $schedule->command('media-library:regenerate --only-missing --force')
        //  ->dailyAt('5:00')->runInBackground();
        $schedule->command('horizon:snapshot')->everyFiveMinutes()
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
