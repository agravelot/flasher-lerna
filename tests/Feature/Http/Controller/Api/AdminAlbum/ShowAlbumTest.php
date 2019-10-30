<?php

namespace Tests\Feature\Http\Controller\Api\AdminAlbum;

use Tests\TestCase;
use App\Models\Album;
use App\Models\PublicAlbum;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_published_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->create();

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertSee($album->title);
    }

    public function test_get_album_contains_links()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertJsonPath('data.links.download', route('download-albums.show', compact('album')))
            ->assertJsonPath('data.links.view', route('albums.show', compact('album')))
            ->assertJsonPath('data.links.edit', "/admin/albums/{$album->slug}/edit");
    }

    private function showAlbum(Album $album): TestResponse
    {
        return $this->json('get', "/api/admin/albums/{$album->slug}");
    }

    public function test_admin_can_view_unpublished_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('unpublished')->create();

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertSee($album->title);
    }

    public function test_admin_can_view_secured_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('password')->create();

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertSee($album->title);
    }

    public function test_admin_can_view_password_less_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('passwordLess')->create();

        $response = $this->showAlbum($album);

        $response->assertOk();
    }

    public function test_user_can_not_view_index()
    {
        $this->actingAsUser();
        $album = factory(PublicAlbum::class)->state('withUser')->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(403);
    }

    public function test_guest_can_not_view_public_album()
    {
        $album = factory(PublicAlbum::class)->state('withUser')->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(401);
    }
}
