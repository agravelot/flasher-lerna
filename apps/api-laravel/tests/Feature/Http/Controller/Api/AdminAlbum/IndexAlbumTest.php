<?php

declare(strict_types=1);

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
        $albums = factory(Album::class, 3)->state('published')->create()->sortByDesc('created_at')->values();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertOk()
            ->assertJsonPath('data.0.title', $albums->get(0)->title)
            ->assertJsonPath('data.1.title', $albums->get(1)->title)
            ->assertJsonPath('data.2.title', $albums->get(2)->title);
    }

    public function test_admin_can_view_unpublished_albums_ordered_by_created_at(): void
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 3)->state('unpublished')->create()->sortByDesc('created_at')->values();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertOk()
            ->assertJsonPath('data.0.title', $albums->get(0)->title)
            ->assertJsonPath('data.1.title', $albums->get(1)->title)
            ->assertJsonPath('data.2.title', $albums->get(2)->title);
    }

    public function test_admin_can_view_secured_albums(): void
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 3)->state('password')->create()->sortByDesc('created_at')->values();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertOk()
            ->assertJsonPath('data.0.title', $albums->get(0)->title)
            ->assertJsonPath('data.1.title', $albums->get(1)->title)
            ->assertJsonPath('data.2.title', $albums->get(2)->title);
    }

    public function test_admin_can_view_password_less_albums(): void
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 3)->state('passwordLess')->create()->sortByDesc('created_at')->values();

        $response = $this->json('get', '/api/admin/albums');

        $response->assertOk()
            ->assertJsonPath('data.0.title', $albums->get(0)->title)
            ->assertJsonPath('data.1.title', $albums->get(1)->title)
            ->assertJsonPath('data.2.title', $albums->get(2)->title);
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
