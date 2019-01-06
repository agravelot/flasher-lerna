<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Front\Album;

use App\Models\Album;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class IndexAlbumTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        Auth::setUser(factory(User::class)->create());
    }

    public function test_guest_view_nothing_to_show()
    {
        $response = $this->showAlbums();

        $response->assertStatus(200);
        $response->assertSee('Nothing to show');
    }

    private function showAlbums(): TestResponse
    {
        return $this->get('/albums');
    }

    public function test_guest_can_view_published_albums()
    {
        $albums = factory(Album::class, 2)->states(['published', 'passwordLess'])->create();

        $response = $this->showAlbums();

        $response->assertStatus(200);
        $response->assertSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }

    public function test_guest_can_not_view_unpublished_albums()
    {
        $albums = factory(Album::class, 2)->states(['unpublished', 'passwordLess'])->create();

        $response = $this->showAlbums();

        $response->assertStatus(200);
        $response->assertDontSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertDontSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }

    public function test_guest_can_not_view_albums_with_password()
    {
        $albums = factory(Album::class, 2)->states(['published', 'password'])->create();

        $response = $this->showAlbums();

        $response->assertStatus(200);
        $response->assertDontSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertDontSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }

    public function test_guest_can_not_view_unpublished_albums_with_password()
    {
        $albums = factory(Album::class, 2)->states(['unpublished', 'password'])->create();

        $response = $this->showAlbums();

        $response->assertStatus(200);
        $response->assertDontSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertDontSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }
}
