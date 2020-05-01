<?php

namespace App\Console\Commands;

use App\Facades\Keycloak;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

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
    public function handle(): bool
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

        $user = new User([]);
        $user->name = $username;
        $user->email = $email;
        $user->password = $password;
        $user->role = $role;

        Keycloak::users()->create($user);
        $this->info('User created successfully');

        return 0;
    }
}
