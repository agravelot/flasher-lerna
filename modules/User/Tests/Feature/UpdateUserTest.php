<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\User\Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanUpdateUser()
    {
        $excepted = 'randomName';
        $this->disableExceptionHandling();
        $this->actingAsAdmin();
        /** @var User $user */
        $user = factory(User::class)->create();

        $user->name = $excepted;
        $response = $this->updateUser($user);

        $response->assertStatus(200);
        $this->assertSame($excepted, $user->fresh()->name);
        $this->assertJsonUserFragment($response, $user);
    }

    private function updateUser(User $user): TestResponse
    {
        return $this->json('patch', "/api/admin/users/{$user->id}", [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }

    private function assertJsonUserFragment(TestResponse $response, User $user): void
    {
        $response->assertJsonFragment([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified_at' => $user->email_verified_at,
                'actions' => ['impersonate' => route('impersonate', $user)],
            ],
        ]);
    }

    public function testUserCanUpdateUser()
    {
        $this->actingAsUser();
        $user = factory(User::class)->create();

        $response = $this->updateUser($user);

        $response->assertStatus(403);
    }

    public function testGuestCanUpdateUser()
    {
        $user = factory(User::class)->create();

        $response = $this->updateUser($user);

        $response->assertStatus(401);
    }

    // TODO Test update role
}
