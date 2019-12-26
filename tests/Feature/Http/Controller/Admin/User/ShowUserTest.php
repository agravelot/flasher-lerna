<?php

namespace Tests\Feature\Http\Controller\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanShowUser()
    {
        $this->actingAsAdmin();
        $user = factory(User::class)->create();

        $response = $this->getUser($user);

        $response->assertOk();
        $this->assertJsonUserFragment($response, $user);
    }

    private function getUser(User $user): TestResponse
    {
        return $this->json('get', "/api/admin/users/{$user->id}");
    }

    private function assertJsonUserFragment(TestResponse $response, User $user): void
    {
        $response->assertJsonFragment([
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }

    public function testUserCanShowUser()
    {
        $this->actingAsUser();
        $user = factory(User::class)->create();

        $response = $this->getUser($user);

        $response->assertStatus(403);
    }

    public function testGuestCanShowUser()
    {
        $user = factory(User::class)->create();

        $response = $this->getUser($user);

        $response->assertStatus(401);
    }

    // TODO Test impersonate
    // TODO test role
}
