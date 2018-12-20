<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Front;

use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class IndexCosplayerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_view_nothing_to_show()
    {
        $response = $this->showCosplayers();

        $response->assertStatus(200);
        $response->assertSee('Nothing to show');
    }

    public function test_guest_can_view_published_albums()
    {
        $cosplayers = factory(Cosplayer::class, 2)->create();

        $response = $this->showCosplayers();

        $response->assertStatus(200);
        $response->assertSee($cosplayers->get(0)->name);
        $response->assertSee($cosplayers->get(0)->description);
        $response->assertSee($cosplayers->get(1)->name);
        $response->assertSee($cosplayers->get(1)->description);
    }

    private function showCosplayers(): TestResponse
    {
        return $this->get('/cosplayers');
    }
}
