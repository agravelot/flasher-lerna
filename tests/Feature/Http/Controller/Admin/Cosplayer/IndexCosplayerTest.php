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

class IndexCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_index_page_with_multiple_cosplayers()
    {
        $this->actingAsAdmin();
        $cosplayers = factory(Cosplayer::class, 5)->create();

        $response = $this->showCosplayerIndex();

        $response->assertStatus(200)
            ->assertDontSee('Nothing to show');

        $cosplayers->each(function (Cosplayer $cosplayer) use ($response) {
            $response->assertSee($cosplayer->name);
        });
    }

    private function showCosplayerIndex(): TestResponse
    {
        return $this->get('/admin/cosplayers');
    }

    public function test_admin_can_view_index_page_with_one_cosplayer()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->showCosplayerIndex();

        $response->assertStatus(200)
            ->assertSee($cosplayer->name)
            ->assertDontSee('Nothing to show');
    }

    public function test_admin_can_view_index_page_with_no_cosplayer()
    {
        $this->actingAsAdmin();

        $response = $this->showCosplayerIndex();

        $response->assertStatus(200)
            ->assertSee('Nothing to show');
    }

    public function test_guest_can_not_view_index_page_for_a_cosplayer_and_is_redirected_to_login()
    {
        $response = $this->showCosplayerIndex();

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_user_can_not_view_index_page_for_a_cosplayer()
    {
        $this->actingAsUser();

        $response = $this->showCosplayerIndex();

        $response->assertStatus(403);
    }
}
