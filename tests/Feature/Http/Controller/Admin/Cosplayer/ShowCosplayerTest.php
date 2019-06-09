<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Cosplayer;

use Tests\TestCase;
use App\Models\User;
use App\Models\Album;
use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_a_cosplayer()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->showCosplayer($cosplayer);

        $response->assertStatus(200)
            // ->assertSee($cosplayer->name)
            ->assertSee($cosplayer->description);
    }

    private function showCosplayer(Cosplayer $cosplayer): TestResponse
    {
        return $this->get("/admin/cosplayers/{$cosplayer->slug}");
    }

    public function test_admin_can_view_a_cosplayer_with_albums()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();
        $albums = factory(Album::class, 2)
            ->states(['published', 'passwordLess'])
            ->make(['user_id' => factory(User::class)->create()->id]);
        $cosplayer->albums()->saveMany($albums);

        $response = $this->showCosplayer($cosplayer);

        $response->assertStatus(200)
            // ->assertSee($cosplayer->name)
            ->assertSee($cosplayer->description);
        $albums->each(function (Album $album) use ($response) {
            $response->assertSee($album->title);
        });
    }

    public function test_user_can_not_view_a_cosplayer()
    {
        $this->actingAsUser();
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->showCosplayer($cosplayer);

        $response->assertForbidden();
    }

    public function test_guest_can_not_view_a_cosplayer_and_is_redirected_to_login()
    {
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->showCosplayer($cosplayer);

        $response->assertRedirect('/login');
        $this->followRedirects($response)
            ->assertStatus(200);
    }
}
