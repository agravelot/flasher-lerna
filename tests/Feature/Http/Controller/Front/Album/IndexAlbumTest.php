<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Front\Album;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class IndexAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_view_no_albums_and_see_nothing_to_show()
    {
        $response = $this->showAlbums();

        $response->assertOk()
            ->assertJson(['data' => []]);
    }

    private function showAlbums(): TestResponse
    {
        return $this->json('get', route('api.albums.index'));
    }

    public function test_guest_can_view_published_albums()
    {
        $albums = factory(Album::class, 2)->states(['published', 'passwordLess', 'withUser'])->create();

        $response = $this->showAlbums();

        $response->assertOk();
        $response->assertSee($albums->get(0)->title);
        $response->assertDontSee($albums->get(0)->body);
        $response->assertSee($albums->get(1)->title);
        $response->assertDontSee($albums->get(1)->body);
    }

    public function test_guest_can_not_view_unpublished_albums()
    {
        $albums = factory(Album::class, 2)->states(['unpublished', 'passwordLess', 'withUser'])->create();

        $response = $this->showAlbums();

        $response->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }

    public function test_guest_can_not_view_albums_with_password()
    {
        $albums = factory(Album::class, 2)->states(['published', 'password', 'withUser'])->create();

        $response = $this->showAlbums();

        $response->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }

    public function test_guest_can_not_view_unpublished_albums_with_password()
    {
        $albums = factory(Album::class, 2)->states(['unpublished', 'password', 'withUser'])->create();

        $response = $this->showAlbums();

        $response->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }
}
