<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Tests\Feature\Http\Controller\Api\Album;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_published_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published', 'passwordLess'])->create();

        $response = $this->json('get', '/api/albums');

        $response->assertStatus(200);
        $this->assertSame($album->title, $response->decodeResponseJson('data')[0]['title']);
    }

    public function test_admin_can_not_view_unpublished_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('unpublished')->create();

        $response = $this->json('get', '/api/albums');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_admin_can_not_view_secured_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('password')->create();

        $response = $this->json('get', '/api/albums');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_user_can_view_index()
    {
        $this->actingAsUser();

        $response = $this->json('get', '/api/albums');

        $response->assertStatus(200);
    }

    public function test_user_can_view_published_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published', 'passwordLess'])->create();

        $response = $this->json('get', '/api/albums');

        $response->assertStatus(200);
        $this->assertSame($album->title, $response->decodeResponseJson('data')[0]['title']);
    }

    public function test_user_can_not_view_unpublished_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('unpublished')->create();

        $response = $this->json('get', '/api/albums');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_user_can_not_view_secured_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('password')->create();

        $response = $this->json('get', '/api/albums');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_guest_can_not_view_index()
    {
        $response = $this->json('get', '/api/albums');

        $response->assertStatus(200);
    }

    public function test_guest_can_view_published_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published', 'passwordLess'])->create();

        $response = $this->json('get', '/api/albums');

        $response->assertStatus(200);
        $this->assertSame($album->title, $response->decodeResponseJson('data')[0]['title']);
    }

    public function test_guest_can_not_view_unpublished_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('unpublished')->create();

        $response = $this->json('get', '/api/albums');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_guest_can_not_view_secured_albums()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('password')->create();

        $response = $this->json('get', '/api/albums');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }
}
