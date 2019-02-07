<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class EditUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_not_view_edit_page_for_a_user_and_is_redirected_to_login()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $response = $this->showUserEdit($user->id);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $response->assertDontSee($user->name);
        $response->assertDontSee($user->email);
    }

    private function showUserEdit(string $id): TestResponse
    {
        return $this->get('/admin/users/' . $id . '/edit');
    }

    public function test_user_can_not_view_edit_page_for_a_user()
    {
        $this->actingAsUser();
        /** @var User $user */
        $user = factory(User::class)->create();

        $response = $this->showUserEdit($user->id);

        $response->assertStatus(403);
        $response->assertDontSee($user->name);
        $response->assertDontSee($user->email);
    }

    public function test_admin_can_view_edit_page_for_a_user()
    {
        $this->actingAsAdmin();
        /** @var User $user */
        $user = factory(User::class)->create();

        $response = $this->showUserEdit($user->id);

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_admin_can_not_edit_non_existent_user()
    {
        $this->actingAsAdmin();

        $response = $this->showUserEdit(-1);

        $response->assertStatus(404);
    }
}
