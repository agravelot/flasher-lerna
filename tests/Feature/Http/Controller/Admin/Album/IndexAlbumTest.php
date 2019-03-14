<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Album;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class IndexAlbumTest extends TestCase
{
    use RefreshDatabase;

    private function getAlbums(): TestResponse
    {
        return  $this->json('get', '/admin/albums');
    }

    public function test_admin_can_view_published_albums()
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('published')->create();

        $response = $this->getAlbums();

        $response->assertStatus(200)
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_admin_can_view_unpublished_albums()
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('unpublished')->create();

        $response = $this->getAlbums();

        $response->assertStatus(200)
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_admin_can_view_secured_albums()
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->states(['published', 'password'])->create();

        $response = $this->getAlbums();

        $response->assertStatus(200)
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_admin_can_view_password_less_albums()
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('passwordLess')->create();

        $response = $this->getAlbums();

        $response->assertStatus(200)
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_user_can_not_view_index()
    {
        $this->actingAsUser();

        $response = $this->getAlbums();

        $response->assertStatus(403);
    }

    public function test_guest_can_not_view_index()
    {
        $response = $this->getAlbums();

        $response->assertStatus(401);
    }
}
