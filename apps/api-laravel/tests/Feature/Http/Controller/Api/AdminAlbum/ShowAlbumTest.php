<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\AdminAlbum;

use App\Models\Album;
use App\Models\PublicAlbum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ShowAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_published_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->create();
        $album->addPicture(UploadedFile::fake()->image('fake.jpg', 200, 300));

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertJsonPath('data.title', $album->title)
            ->assertJsonPath('data.medias.0.width', 200)
            ->assertJsonPath('data.medias.0.height', 300);
    }

    public function test_get_album_contains_links(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertJsonPath('data.links.edit', "/admin/albums/{$album->slug}/edit");
    }

    private function showAlbum(Album $album): TestResponse
    {
        return $this->json('get', "/api/admin/albums/{$album->slug}");
    }

    public function test_admin_can_view_unpublished_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('unpublished')->create();

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertSee($album->title);
    }

    public function test_admin_can_view_secured_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('password')->create();

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertSee($album->title);
    }

    public function test_admin_can_view_password_less_albums(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('passwordLess')->create();

        $response = $this->showAlbum($album);

        $response->assertOk();
    }

    public function test_user_can_not_view_index(): void
    {
        $this->actingAsUser();
        $album = factory(PublicAlbum::class)->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(403);
    }

    public function test_guest_can_not_view_public_album(): void
    {
        $album = factory(PublicAlbum::class)->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(401);
    }
}
