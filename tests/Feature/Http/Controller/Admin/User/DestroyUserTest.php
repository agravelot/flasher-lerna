<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\User;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class DestroyUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_destroy_a_user()
    {
        $this->actingAsAdmin();

        /* @var User $user */
        $user = factory(User::class)->create();

        $response = $this->deleteUser($user->id);

        $this->assertNull($user->fresh());
        $response->assertStatus(302);
        $response->assertRedirect('/admin/users');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee('User successfully deleted')
            ->assertDontSee($user->name)
            ->assertDontSee($user->email);
    }

    private function deleteUser(string $id): TestResponse
    {
        return $this->delete('/admin/users/' . $id);
    }

    public function test_user_can_not_destroy_a_user()
    {
        $this->actingAsUser();

        /* @var User $user */
        $user = factory(User::class)->create();

        $response = $this->deleteUser($user->id);

        $this->assertNotNull($user->fresh());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_destroy_a_user_and_is_redirected_to_login()
    {
        /* @var User $user */
        $user = factory(User::class)->create();

        $response = $this->deleteUser($user->id);

        $this->assertNotNull($user->fresh());
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
