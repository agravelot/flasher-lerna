<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Console\Commands;

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
    public function handle()
    {
        $role = $this->argument('role');
        $username = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        if ($role === null) {
            $role = $this->choice('Please select a user role', ['admin', 'user'], 'user');
        }

        if ($username === null) {
            $username = $this->ask('Please enter a username');
        }

        if ($email === null) {
            $email = $this->ask('Enter your user email');
        }

        if ($password === null) {
            $password = $this->secret('Enter your user password');
        }

        $data = [
            'name' => $username,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ];

        // Only get filtered keys for name and email
        $filter = array_fill_keys(['name', 'email'], '');
        $rules = array_intersect_key((new UserRequest())->rules(), $filter);

        if (! $this->validate($data, $rules)) {
            return 1;
        }
        $this->createUser($data);

        $this->info('User created successfully');

        return 0;
    }

    private function validate(array $data, array $rules): bool
    {
        $validator = Validator::make($data, $rules);
        $validator->errors()->all();

        if ($validator->fails()) {
            $this->error('User not created. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->warn($error);
            }

            return false;
        }

        return true;
    }

    private function createUser($data)
    {
        $user = User::create($data);
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
    }
}
