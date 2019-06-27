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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexUserTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanIndexUsers()
    {
        $this->actingAsAdmin();
        $users = factory(User::class, 5)->create();

        $response = $this->getUsers();

        $response->assertStatus(200);
        $this->assertJsonUserFragment($response, $users);
    }

    private function getUsers(): TestResponse
    {
        return $this->json('get', '/api/admin/users');
    }

    private function assertJsonUserFragment(TestResponse $response, Collection $users): void
    {
        $users->each(function (User $user) use ($response) {
            $response->assertJsonFragment([
                'name' => $user->name,
                'email' => $user->email,
            ]);
        });
    }

    public function testUserCanIndexUsers()
    {
        $this->actingAsUser();

        $response = $this->getUsers();

        $response->assertStatus(403);
    }

    public function testGuestCanIndexUsers()
    {
        $response = $this->getUsers();

        $response->assertStatus(401);
    }
}
