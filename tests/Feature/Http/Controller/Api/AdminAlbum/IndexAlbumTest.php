<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Api\AdminAlbum;

use Tests\TestCase;
use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_published_albums()
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('published')->create();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertStatus(200)
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_admin_can_view_unpublished_albums()
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('unpublished')->create();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertStatus(200)
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_admin_can_view_secured_albums()
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('password')->create();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertStatus(200)
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_admin_can_view_password_less_albums()
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('passwordLess')->create();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertStatus(200);
    }

    public function test_user_can_not_view_index()
    {
        $this->actingAsUser();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertStatus(403);
    }

    public function test_guest_can_not_view_index()
    {
        $response = $this->json('get', '/api/admin/albums');

        $response->assertStatus(401);
    }
}
