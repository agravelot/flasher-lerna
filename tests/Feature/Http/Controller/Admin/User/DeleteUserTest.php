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

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_an_user(): void
    {
        $this->actingAsAdmin();
        $user = factory(User::class)->create();

        $response = $this->deleteUser($user);

        $response->assertStatus(204);
        $this->assertNull($user->fresh());
    }

    private function deleteUser(User $user): TestResponse
    {
        return $this->json('delete', "/api/admin/users/{$user->id}");
    }

    public function test_user_cannot_delete_another_user(): void
    {
        $this->actingAsUser();
        $user = factory(User::class)->create();

        $response = $this->deleteUser($user);

        $response->assertStatus(403);
        $this->assertNotNull($user->fresh());
    }

    public function test_guest_cannot_delete_another_user(): void
    {
        $user = factory(User::class)->create();

        $response = $this->deleteUser($user);

        $response->assertStatus(401);
        $this->assertNotNull($user->fresh());
    }
}
