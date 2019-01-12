<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Album;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class StoreAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_an_album()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();

        $this->storeAlbum($album);

        $this->assertSame(1, Album::count());
    }

    public function storeAlbum(Album $album): TestResponse
    {
        return $this->post('/admin/albums', $album->toArray());
    }

    public function test_admin_cannot_store_an_album_with_published_now()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->make();

        $this->storeAlbum($album);

        $this->assertSame(1, Album::count());
    }

    public function test_user_cannot_store_an_album()
    {
        $this->actingAsUser();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(0, Album::count());
        $response->assertStatus(403);
    }

    public function test_guest_cannot_store_an_album()
    {
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(0, Album::count());
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }
}
