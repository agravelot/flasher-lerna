<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Tests\Feature\Http\Controller\Api\Album;

use Tests\TestCase;
use App\Models\Album;
use App\Models\PublicAlbum;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_public_albums()
    {
        $this->actingAsAdmin();
        $album = factory(PublicAlbum::class)->state('withUser')->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(200)
            ->assertSee($album->title);
    }

    private function showAlbum(Album $album): TestResponse
    {
        return $this->json('get', "/api/albums/{$album->slug}");
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

        $response->assertStatus(200);
    }

    public function test_guest_can_view_public_album()
    {
        $album = factory(PublicAlbum::class)->state('withUser')->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(200);
    }
}
