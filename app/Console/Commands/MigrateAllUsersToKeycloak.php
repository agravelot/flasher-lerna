<?php

namespace App\Console\Commands;

use App\Adapters\Keycloak\Credential;
use App\Adapters\Keycloak\UserQuery;
use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
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
        AddKeycloakUsers::dispatchNow(DB::table('users_save')->get()->map(static function ($data) {
            $user = new UserRepresentation();
            $user->email = $data->email;
            $user->username = $data->name;
            $user->addCredential(new Credential($data->password));
            $user->groups = $data->role === 'admin' ? ['admin'] : [];
            $user->emailVerified = (bool) $data->email_verified_at;
            $user->attributes = ['notify_on_album_published' => $data->notify_on_album_published];

            return $user;
        }));

        $this->migrateTable('albums');
        $this->migrateTable('cosplayers');
        $this->migrateTable('posts');
        $this->migrateTable('testimonials');
        $this->migrateTable('contacts');
        $this->migrateTable('comments');

        return true;
    }

    private function migrateTable(string $table): void
    {
        $this->info("Updating $table with newer sso ids");
        DB::table($table)->whereNotNull('user_id')->distinct()->get('user_id')->each(static function (\stdClass $data) use ($table) {
            $query = new UserQuery();
            $query->email = DB::table('users_save')->find($data->user_id)->email;
            $user = Keycloak::users()->first($query);
            DB::table($table)->where('user_id', $data->user_id)->update(['sso_id' => $user->id]);
        });
    }
}
