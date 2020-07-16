<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\Album;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_published_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published', 'passwordLess'])->create();

        $response = $this->json('get', '/api/albums');

        $response->assertOk();
        $this->assertSame($album->title, $response->decodeResponseJson('data')[0]['title']);
    }

    public function test_admin_can_not_view_unpublished_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('unpublished')->create();

        $response = $this->json('get', '/api/albums');

        $response->assertOk()
            ->assertJson(['data' => []]);
    }

    public function test_admin_can_not_view_secured_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('password')->create();

        $response = $this->json('get', '/api/albums');

        $response->assertOk()
            ->assertJson(['data' => []]);
    }

    public function test_user_can_view_published_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published', 'passwordLess', 'withMedias'])->create();

        $response = $this->json('get', '/api/albums');

        $response->assertOk()
            ->assertJsonPath('data.0.media.name', 'fake')
            ->assertJsonPath('data.0.title', $album->title);
    }

    public function test_user_can_not_view_unpublished_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('unpublished')->create();

        $response = $this->json('get', '/api/albums');

        $response->assertOk()
            ->assertJson(['data' => []]);
    }

    public function test_user_can_not_view_secured_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('password')->create();

        $response = $this->json('get', '/api/albums');

        $response->assertOk()
            ->assertJson(['data' => []]);
    }

    public function test_guest_can_not_view_index(): void
    {
        $response = $this->json('get', '/api/albums');

        $response->assertOk();
    }

    public function test_guest_can_view_published_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->states(['published', 'passwordLess'])->create();

        $response = $this->json('get', '/api/albums');

        $response->assertOk();
        $this->assertSame($album->title, $response->decodeResponseJson('data')[0]['title']);
    }

    public function test_guest_can_not_view_unpublished_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('unpublished')->create();

        $response = $this->json('get', '/api/albums');

        $response->assertOk()
            ->assertJson(['data' => []]);
    }

    public function test_guest_can_not_view_secured_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('password')->create();

        $response = $this->json('get', '/api/albums');

        $response->assertOk()
            ->assertJson(['data' => []]);
    }
}
