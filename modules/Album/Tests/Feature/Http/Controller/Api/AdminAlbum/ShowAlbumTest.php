<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Tests\Feature\Http\Controller\Api\AdminAlbum;

use App\Models\Album;
use App\Models\PublicAlbum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ShowAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_published_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(200)
            ->assertSee($album->title);
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

        $response->assertStatus(200)
            ->assertSee($album->title);
    }

    public function test_admin_can_view_secured_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('password')->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(200)
            ->assertSee($album->title);
    }

    public function test_admin_can_view_password_less_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('passwordLess')->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(200);
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
