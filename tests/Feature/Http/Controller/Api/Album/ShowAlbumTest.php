<?php

namespace Tests\Feature\Http\Controller\Api\Album;

use App\Models\Album;
use App\Models\PublicAlbum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ShowAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_public_albums()
    {
        $this->actingAsAdmin();
        $album = factory(PublicAlbum::class)->state('withUser')->create();

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertSee($album->title);
    }

    private function showAlbum(Album $album): TestResponse
    {
        return $this->json('get', "/api/albums/{$album->slug}");
    }

    public function test_admin_can_download_albums()
    {
        $this->actingAsAdmin();
        $album = factory(PublicAlbum::class)->state('withUser')->create();

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'links' => [
                        'download' => route('download-albums.show', ['album' => $album]),
                    ],
                ],
            ]);
    }

    public function test_admin_can_download_albums_as_non_ajax_request()
    {
        $this->actingAsAdmin();
        $album = factory(PublicAlbum::class)->state('withUser')->create();

        $response = $this->json('get', "/api/albums/{$album->slug}");

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'links' => [
                        'download' => route('download-albums.show', ['album' => $album]),
                    ],
                ],
            ]);
    }

    public function test_admin_can_not_view_unpublished_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('unpublished')->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(404);
    }

    public function test_admin_can_not_view_secured_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('password')->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(404);
    }

    public function test_admin_get_404_on_bad_slug()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('password')->create();

        $album->slug = 'random-slug-0000';
        $response = $this->showAlbum($album);

        $response->assertStatus(404);
    }

    public function test_user_can_view_public_album()
    {
        $this->actingAsUser();
        $album = factory(PublicAlbum::class)->state('withUser')->create();

        $response = $this->showAlbum($album);

        $response->assertOk();
    }

    public function test_guest_can_view_public_album()
    {
        $album = factory(PublicAlbum::class)->state('withUser')->create();

        $response = $this->showAlbum($album);

        $response->assertOk();
    }
}
