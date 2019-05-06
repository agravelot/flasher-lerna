<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Cosplayer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_not_view_create_page_for_a_cosplayer_and_is_redirected_to_login()
    {
        $response = $this->showCosplayerCreate();

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    private function showCosplayerCreate(): TestResponse
    {
        return $this->get('/admin/cosplayers/create');
    }

    public function test_user_can_not_view_create_page_for_a_cosplayer()
    {
        $this->actingAsUser();

        $response = $this->showCosplayerCreate();

        $response->assertStatus(403);
    }

    public function test_admin_can_view_create_page_for_a_cosplayer()
    {
        $this->actingAsAdmin();

        $response = $this->showCosplayerCreate();

        $response->assertStatus(200);
    }
}
