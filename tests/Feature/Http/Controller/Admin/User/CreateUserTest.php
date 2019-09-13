<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_user(): void
    {
        $this->actingAsAdmin();
        $user = factory(User::class)->make();

        $response = $this->createUser($user, 'randomPassword');

        $response->assertStatus(201);
        $this->assertNotNull(User::where('email', $user->email)->first());
    }

    public function createUser(User $user, ?string $password = null, ?string $passwordConfirmation = null): TestResponse
    {
        return $this->json('post', '/api/admin/users', [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation ?: $password,
        ]);
    }

    public function test_admin_cannot_create_user_with_too_short_password(): void
    {
        $this->actingAsAdmin();
        $user = factory(User::class)->make();

        $response = $this->createUser($user, 'short');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password' => 'The password must be at least 6 characters.']);
        $this->assertNull(User::where('email', $user->email)->first());
    }

    public function test_admin_cannot_create_user_with_empty_password(): void
    {
        $this->actingAsAdmin();
        $user = factory(User::class)->make();

        $response = $this->createUser($user);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password' => 'The password field is required.']);
        $this->assertNull(User::where('email', $user->email)->first());
    }

    public function test_admin_cannot_create_user_with_different_password(): void
    {
        $this->actingAsAdmin();
        $user = factory(User::class)->make();

        $response = $this->createUser($user, 'randomPassword', 'otherRandomPassword');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password' => 'The password confirmation does not match.']);
        $this->assertNull(User::where('email', $user->email)->first());
    }

    public function test_admin_cannot_create_user_with_same_email(): void
    {
        $this->actingAsAdmin();
        $originalUser = factory(User::class)->create();
        $user = factory(User::class)->make(['email' => $originalUser->email]);

        $response = $this->createUser($user, 'randomPassword');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email' => 'The email has already been taken.']);
        $this->assertCount(1, User::where('email', $user->email)->get());
    }

    public function test_user_cannot_create_user_with_same_email(): void
    {
        $this->actingAsUser();
        $user = factory(User::class)->make();

        $response = $this->createUser($user, 'randomPassword');

        $response->assertStatus(403);
        $this->assertNull(User::where('email', $user->email)->first());
    }

    public function test_guest_cannot_create_user_with_same_email(): void
    {
        $user = factory(User::class)->make();

        $response = $this->createUser($user, 'randomPassword');

        $response->assertStatus(401);
        $this->assertNull(User::where('email', $user->email)->first());
    }
}
