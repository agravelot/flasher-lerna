<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Tests\Feature\Http\Controller\Api\AdminAlbum;

use Tests\TestCase;
use App\Models\Album;
use App\Models\Category;
use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_an_album_without_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->media->first);
        $response->assertStatus(201);
    }

    public function storeAlbum(Album $album, array $optional = []): TestResponse
    {
        session()->setPreviousUrl('/admin/albums/create');

        return $this->json('post', '/api/admin/albums', array_merge($album->toArray(), $optional));
    }

    public function test_admin_can_store_an_album_with_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->media->first);
        $response->assertStatus(201);
    }

    public function test_admin_can_store_an_album_with_a_category_and_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();
        $category = factory(Category::class)->create();

        $response = $this->storeAlbum($album, [
            'categories' => [['id' => $category->id]],
        ]);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->categories->first);
        $response->assertStatus(201);
    }

    public function test_admin_can_not_store_an_album_with_an_non_existent_category_and_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album, [
            'categories' => [['id' => 42]],
        ]);

        $this->assertSame(0, Album::count());
        $response->assertStatus(422);
    }

    public function test_admin_can_store_an_album_with_a_cosplayer_and_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->storeAlbum($album, [
            'cosplayers' => [['id' => $cosplayer->id]],
        ]);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->categories->first);
        $response->assertStatus(201);
    }

    public function test_admin_can_store_an_album_with_an_non_existent_cosplayer()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album, [
            'cosplayers' => [['id' => 42]],
        ]);

        $this->assertSame(0, Album::count());
        $response->assertStatus(422);
    }

    public function test_admin_can_store_an_album_with_a_multiple_pictures()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertStatus(201);
    }

    public function test_admin_can_store_an_album_with_published_now()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertStatus(201);
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
        $response->assertStatus(401);
    }
}
