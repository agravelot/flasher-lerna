<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\User;

use App\Models\Album;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_a_user()
    {
        $this->actingAsAdmin();
        $user = factory(User::class)->create();

        $response = $this->showUser($user);

        $response->assertStatus(200)
            ->assertSee($user->name);
    }

    private function showUser(User $user): TestResponse
    {
        return $this->get('/admin/users/' . $user->id);
    }

    public function test_admin_can_view_a_user_with_albums()
    {
        $this->actingAsAdmin();
        $user = factory(User::class)->create();
        $albums = factory(Album::class, 2)
            ->states(['published', 'passwordLess'])
            ->make();
        $user->albums()->saveMany($albums);

        $response = $this->showUser($user);

        $response->assertStatus(200)
            ->assertSee($user->name);
        $albums->each(function (Album $album) use ($response) {
            $response->assertSee($album->title);
        });
    }

    public function test_user_can_not_view_a_user()
    {
        $this->actingAsUser();
        $user = factory(User::class)->create();

        $response = $this->showUser($user);

        $response->assertForbidden();
    }

    public function test_guest_can_not_view_a_user_and_is_redirected_to_login()
    {
        $user = factory(User::class)->create();

        $response = $this->showUser($user);

        $response->assertRedirect('/login');
        $this->followRedirects($response)
            ->assertStatus(200);
    }
}
