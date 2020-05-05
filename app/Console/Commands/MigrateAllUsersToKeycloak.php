<?php

namespace App\Console\Commands;

use App\Jobs\AddKeycloakUsers;
use App\Jobs\CleanAllKeycloakUsers;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateAllUsersToKeycloak extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keycloak:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all users from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleanup all user from keycloak');
        CleanAllKeycloakUsers::dispatchNow();
        $this->info('Migrating users from database');
        AddKeycloakUsers::dispatchNow(DB::table('users_save')->get());

        return true;
    }
}
