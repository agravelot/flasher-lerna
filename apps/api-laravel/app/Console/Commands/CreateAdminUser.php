<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Adapters\Keycloak\Credential;
use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create
                            {role? : Role}
                            {name? : Username}
                            {email? : Email-address}
                            {password? : Password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $role = $this->argument('role');
        $username = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        if (! $role) {
            $role = $this->choice('Please select a user role', ['admin', 'user'], 'user');
        }

        if (! $username) {
            $username = $this->ask('Please enter a username');
        }

        if (! $email) {
            $email = $this->ask('Enter your user email');
        }

        if (! $password) {
            $password = $this->secret('Enter your user password');
        }

        $user = new UserRepresentation();
        $user->username = $username;
        $user->email = $email;
        $user->emailVerified = true;
        $user->addCredential(new Credential(Hash::make($password)));
        $user->groups = [$role];

        Keycloak::users()->create($user);
        $this->info('User created successfully');
    }
}
