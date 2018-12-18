<?php

namespace Tests\Feature\Front\Album;

use App\Models\Album;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IndexAlbumTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_view_nothing_to_show()
    {
        $response = $this->get('/albums');

        $response->assertStatus(200);
        $response->assertSee('Nothing to show');
    }

    public function test_guest_can_view_published_albums()
    {
        $albums = factory(Album::class, 2)->states(['published', 'passwordLess', 'withUser'])->create();

        $response = $this->get('/albums');

        $response->assertStatus(200);
        $response->assertSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }

    public function test_guest_can_not_view_unpublished_albums()
    {
        $albums = factory(Album::class, 2)->states(['unpublished', 'passwordLess', 'withUser'])->create();

        $response = $this->get('/albums');

        $response->assertStatus(200);
        $response->assertDontSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertDontSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }


    public function test_guest_can_not_view_albums_with_password()
    {
        $albums = factory(Album::class, 2)->states(['published', 'password', 'withUser'])->create();

        $response = $this->get('/albums');

        $response->assertStatus(200);
        $response->assertDontSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertDontSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }

    public function test_guest_can_not_view_unpublished_albums_with_password()
    {
        $albums = factory(Album::class, 2)->states(['unpublished', 'password', 'withUser'])->create();

        $response = $this->get('/albums');

        $response->assertStatus(200);
        $response->assertDontSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertDontSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }
}