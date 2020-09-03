<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Facades\Keycloak;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_without_argument(): void
    {
        Keycloak::shouldReceive('users->create')->once()->withAnyArgs()->andReturn();
        $this->artisan('user:create')
            ->expectsQuestion('Please select a user role', 'admin')
            ->expectsQuestion('Please enter a username', 'admin')
            ->expectsQuestion('Enter your user email', 'admin@picblog.com')
            ->expectsQuestion('Enter your user password', 'admin')
            ->expectsOutput('User created successfully')
            ->assertExitCode(0);
    }

    public function test_create_user_with_argument(): void
    {
        Keycloak::shouldReceive('users->create')->once()->withAnyArgs()->andReturn();
        $this->artisan('user:create', [
            'role' => 'admin',
            'name' => 'admin',
            'email' => 'admin@picblog.com',
            'password' => 'secret',
        ])
            ->expectsOutput('User created successfully')
            ->assertExitCode(0);
    }
}
