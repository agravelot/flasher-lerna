<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
    public function handle()
    {
        $this->info('Waiting database');
        $this->waitConnection();
    }

    public function waitConnection()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            sleep(1);
            $this->warn('Unable to connect to the database, retrying...');
            $this->waitConnection();
        }
    }
}
