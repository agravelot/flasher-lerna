<?php

namespace App\Console\Commands;

use App\Jobs\Credential;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MigrateUserToKeycloak extends Command
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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Start migration');

        $baseUrl = 'https://keycloak.localhost/auth';
        $username = 'admin';
        $password = 'admin';
        $grant_type = 'password';
        $clientId = 'admin-cli';

        $response = Http::withOptions(['verify' => false])
            ->asForm()->post(
                "$baseUrl/realms/master/protocol/openid-connect/token",
                [
                    'username' => $username,
                    'password' => $password,
                    'grant_type' => $grant_type,
                    'client_id' => $clientId,
                ]
            );
        $accessToken = $response->json()['access_token'];

        $users = Http::withOptions(['verify' => false])
            ->withToken($accessToken)->get("$baseUrl/admin/realms/jkanda/users")->json();

        foreach ($users as $user) {
            $id = $user['id'];
            Http::withOptions(['verify' => false, 'debug' => false])
                ->withToken($accessToken)
                ->delete("$baseUrl/admin/realms/jkanda/users/$id");
            $this->info("Deleting user $id");
        }

        User::cursor()->each(
            function (User $user) use ($accessToken, $baseUrl) {
                $this->info('Migrating user : '.$user->name);

                $response = Http::withOptions(['verify' => false, 'debug' => false])
                    ->withToken($accessToken)
                    ->post("$baseUrl/admin/realms/jkanda/users", [
                        'email' => $user->email,
                        'username' => $user->name,
                        'emailVerified' => (bool) $user->email_verified_at,
                        'enabled' => true,
                        'totp' => false,
                        'credentials' => [new Credential($user->password)],
                        'groups' => $user->role === 'admin' ? ['admin'] : [],
                        'attributes' => [
                            'notifyOnAlbumPublished' => $user->notify_on_album_published,
                        ],
                    ]);

                if ($response->status() === 201) {
                    $this->info('✅ User created');
                } else {
                    $this->info('❌ Unable to create user : '.$response->status());
                }
            });
    }
}
