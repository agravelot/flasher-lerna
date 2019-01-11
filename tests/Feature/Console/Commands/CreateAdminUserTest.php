<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Console\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_without_argument()
    {
        $this->artisan('user:create')
            ->expectsQuestion('Please select a user role', 'admin')
            ->expectsQuestion('Please enter a username', 'admin')
            ->expectsQuestion('Enter your user email', 'admin@picblog.com')
            ->expectsQuestion('Enter your user password', 'admin')
            ->expectsOutput('User created successfully')
            ->assertExitCode(0);
    }

    public function test_create_user_with_argument()
    {
        $this->artisan('user:create', [
            'role' => 'admin',
            'name' => 'admin',
            'email' => 'admin@picblog.com',
            'password' => 'secret',
        ])
            ->expectsOutput('User created successfully')
            ->assertExitCode(0);
    }

    public function test_can_not_create_user_with_malformed_email()
    {
        $this->artisan('user:create', [
            'role' => 'admin',
            'name' => 'admin',
            'email' => 'admin@picblog',
            'password' => 'secret',
        ])
            ->expectsOutput('User not created. See error messages below:')
            ->expectsOutput('The email must be a valid email address.')
            ->assertExitCode(1);
    }

    public function test_can_not_create_user_with_malformed_username()
    {
        $this->artisan('user:create', [
            'role' => 'admin',
            'name' => 'admin',
            'email' => 'admin@picblog',
            'password' => 'secret',
        ])
            ->expectsOutput('User not created. See error messages below:')
            ->expectsOutput('The email must be a valid email address.')
            ->assertExitCode(1);
    }

    public function test_create_user_with_argument_is_stores_in_database()
    {
        Artisan::call('user:create', [
            'role' => 'admin',
            'name' => 'admin',
            'email' => 'admin@picblog.com',
            'password' => 'secret',
        ]);

        $user = User::latest()->first();
        $this->assertSame('admin', $user->name);
        $this->assertSame('admin', $user->role);
        $this->assertSame('admin@picblog.com', $user->email);
        $this->assertTrue(Hash::check('secret', $user->password));
    }
}
