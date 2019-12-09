<?php

namespace Tests\Feature\Console\Commands;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_without_argument(): void
    {
        $this->artisan('user:create')
            ->expectsQuestion('Please select a user role', 'admin')
            ->expectsQuestion('Please enter a username', 'admin')
            ->expectsQuestion('Enter your user email', 'admin@picblog.com')
            ->expectsQuestion('Enter your user password', 'admin')
            ->expectsOutput('User created successfully')
            ->assertExitCode(0);
        $this->assertCount(1, User::all());
    }

    public function test_create_user_with_argument(): void
    {
        $this->artisan('user:create', [
            'role' => 'admin',
            'name' => 'admin',
            'email' => 'admin@picblog.com',
            'password' => 'secret',
        ])
            ->expectsOutput('User created successfully')
            ->assertExitCode(0);
        $this->assertCount(1, User::all());
    }

    public function test_can_not_create_user_with_malformed_email(): void
    {
        $this->artisan('user:create', [
            'role' => 'admin',
            'name' => 'admin',
            'email' => 'admin',
            'password' => 'secret',
        ])
            ->expectsOutput('User not created. See error messages below:')
            ->expectsOutput('The email must be a valid email address.')
            ->assertExitCode(1);
        $this->assertCount(0, User::all());
    }

    public function test_can_not_create_user_with_malformed_username(): void
    {
        $this->artisan('user:create', [
            'role' => 'admin',
            'name' => 'admin',
            'email' => 'admin',
            'password' => 'secret',
        ])
            ->expectsOutput('User not created. See error messages below:')
            ->expectsOutput('The email must be a valid email address.')
            ->assertExitCode(1);
        $this->assertCount(0, User::all());
    }

    public function test_create_user_with_argument_is_stores_in_database(): void
    {
        Artisan::call('user:create', [
            'role' => 'admin',
            'name' => 'admin',
            'email' => 'admin@picblog.com',
            'password' => 'secret',
        ]);

        $this->assertNotNull($user = User::latest()->first());
        $this->assertSame('admin', $user->name);
        $this->assertSame('admin', $user->role);
        $this->assertSame('admin@picblog.com', $user->email);
        $this->assertTrue(Hash::check('secret', $user->password));
    }
}
