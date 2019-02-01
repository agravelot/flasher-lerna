<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Cosplayer;

use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class EditCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_not_view_edit_page_for_a_cosplayer_and_is_redirected_to_login()
    {
        /** @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->showCosplayerEdit($cosplayer->slug);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $response->assertDontSee($cosplayer->name);
        $response->assertDontSee($cosplayer->description);
    }

    private function showCosplayerEdit(string $slug): TestResponse
    {
        return $this->get('/admin/cosplayers/' . $slug . '/edit');
    }

    public function test_user_can_not_view_edit_page_for_a_cosplayer()
    {
        $this->actingAsUser();
        /** @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->showCosplayerEdit($cosplayer->slug);

        $response->assertStatus(403);
        $response->assertDontSee($cosplayer->name);
        $response->assertDontSee($cosplayer->description);
    }

    public function test_admin_can_view_edit_page_for_a_cosplayer()
    {
        $this->actingAsAdmin();
        /** @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->showCosplayerEdit($cosplayer->slug);

        $response->assertStatus(200);
        $response->assertSee($cosplayer->name);
        $response->assertSee($cosplayer->description);
    }

    public function test_admin_can_not_edit_inexistent_cosplayer()
    {
        $this->actingAsAdmin();

        $response = $this->showCosplayerEdit('random-slug');

        $response->assertStatus(404);
    }
}
