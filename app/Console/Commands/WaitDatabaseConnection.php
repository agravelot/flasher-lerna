<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WaitDatabaseConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:wait-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wait until get database connection';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Waiting database');
        $this->waitConnection();
    }

    public function waitConnection(): void
    {
        try {
            DB::connection()->getPdo();
        } catch (Exception $exception) {
            Log::error($exception->getTraceAsString());
            sleep(1);
            $this->warn('Unable to connect to the database, retrying...');
            $this->waitConnection();
        }
    }
}
