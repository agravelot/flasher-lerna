<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Front\Cosplayer;

use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ShowCosplayerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_can_view_a_cosplayer()
    {
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->showCosplayers($cosplayer->slug);

        $response->assertStatus(200);
        $response->assertSee($cosplayer->name);
        $response->assertSee($cosplayer->description);
    }

    public function test_guest_can_not_view_an_unknown_cosplayer()
    {
        $response = $this->showCosplayers('some-random-slug');

        $response->assertStatus(404);
    }

    private function showCosplayers(string $slug): TestResponse
    {
        return $this->get('/cosplayers/' . $slug);
    }
}
