<?php

namespace Tests\Feature\Http\Controller\Api\AdminAlbum;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_published_albums(): void
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 3)->state('published')->create();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertOk()
            ->assertJsonPath('data.0.title', $albums->get(0)->title)
            ->assertJsonPath('data.1.title', $albums->get(1)->title)
            ->assertJsonPath('data.1.title', $albums->get(1)->title);
    }

    public function test_admin_can_view_unpublished_albums(): void
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('unpublished')->create();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertOk()
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_admin_can_view_secured_albums(): void
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('password')->create();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertOk()
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_admin_can_view_password_less_albums(): void
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('passwordLess')->create();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertOk();
    }

    public function test_user_can_not_view_index(): void
    {
        $this->actingAsUser();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertStatus(403);
    }

    public function test_guest_can_not_view_index(): void
    {
        $response = $this->json('get', '/api/admin/albums');

        $response->assertStatus(401);
    }
}
