<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Front\Album;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class IndexAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_view_no_albums_and_see_nothing_to_show()
    {
        $response = $this->showAlbums();

        $response->assertStatus(200);
        $response->assertSee('Nothing to show');
    }

    private function showAlbums(): TestResponse
    {
        return $this->get(route('albums.index'));
    }

    public function test_guest_can_view_published_albums()
    {
        $albums = factory(Album::class, 2)->states(['published', 'passwordLess', 'withUser'])->create();

        $response = $this->showAlbums();

        $response->assertStatus(200);
        $response->assertSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }

    public function test_guest_can_not_view_unpublished_albums()
    {
        $albums = factory(Album::class, 2)->states(['unpublished', 'passwordLess', 'withUser'])->create();

        $response = $this->showAlbums();

        $response->assertStatus(200);
        $response->assertDontSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertDontSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }

    public function test_guest_can_not_view_albums_with_password()
    {
        $albums = factory(Album::class, 2)->states(['published', 'password', 'withUser'])->create();

        $response = $this->showAlbums();

        $response->assertStatus(200);
        $response->assertDontSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertDontSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }

    public function test_guest_can_not_view_unpublished_albums_with_password()
    {
        $albums = factory(Album::class, 2)->states(['unpublished', 'password', 'withUser'])->create();

        $response = $this->showAlbums();

        $response->assertStatus(200);
        $response->assertDontSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertDontSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }
}
