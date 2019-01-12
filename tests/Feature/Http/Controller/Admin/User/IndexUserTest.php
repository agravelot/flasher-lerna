<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class IndexUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_index_page_with_multiple_users()
    {
        $this->actingAsAdmin();
        $users = factory(User::class, 2)->create();

        $response = $this->showUserIndex();

        $response->assertStatus(200)
            ->assertDontSee('Nothing to show');

        $users->each(function (User $user) use ($response) {
            $response->assertSee($user->name);
        });
    }

    private function showUserIndex(): TestResponse
    {
        return $this->get('/admin/users');
    }

    public function test_admin_can_view_index_page_with_one_user()
    {
        $this->actingAsAdmin();
        $user = factory(User::class)->create();

        $response = $this->showUserIndex();

        $response->assertStatus(200)
            ->assertSee($user->name)
            ->assertDontSee('Nothing to show');
    }

    public function test_admin_can_view_index_page_with_no_user()
    {
        $this->actingAsAdminNotStored();

        $response = $this->showUserIndex();

        $response->assertStatus(200)
            ->assertSee('Nothing to show');
    }

    public function test_guest_can_not_view_index_page_for_a_user_and_is_redirected_to_login()
    {
        $response = $this->showUserIndex();

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_user_can_not_view_index_page_for_a_user()
    {
        $this->actingAsUser();

        $response = $this->showUserIndex();

        $response->assertStatus(403);
    }
}
