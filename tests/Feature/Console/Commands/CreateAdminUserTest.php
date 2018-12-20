<?php

namespace Tests\Feature\Console\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminUserTest extends TestCase
{
    use DatabaseMigrations;

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

    public function test_create_user_with_malformed_email()
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

    public function test_create_user_with_malformed_username()
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

        $this->assertCount(1, User::all());
        $user = User::find(1);
        $this->assertEquals('admin', $user->name);
        $this->assertEquals('admin', $user->role);
        $this->assertEquals('admin@picblog.com', $user->email);
        $this->assertTrue(Hash::check('secret', $user->password));
    }
}