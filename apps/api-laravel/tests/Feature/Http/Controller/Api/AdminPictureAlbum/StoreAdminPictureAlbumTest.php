<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\AdminPictureAlbum;

use App\Jobs\PerformConversions;
use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreAdminPictureAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_a_picture_to_an_album_with_dimensions(): void
    {
        Queue::fake();
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg', 4096, 3826);
        Queue::assertNotPushed(PerformConversions::class);

        $response = $this->storeAlbumPicture($album, $image);

        $this->assertSame(1, $album->fresh()->media->count());
        $response->assertCreated();
        $this->assertSame(4096, $album->fresh()->media->first()->getCustomProperty('width'));
        $this->assertSame(3826, $album->fresh()->media->first()->getCustomProperty('height'));
        Queue::assertPushedOn('images', PerformConversions::class);
    }

    public function test_user_can_not_store_a_picture_to_an_album(): void
    {
        Queue::fake();
        $this->actingAsUser();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album, $image);

        $this->assertSame(0, $album->fresh()->media->count());
        $response->assertStatus(403);
        Queue::assertNotPushed(PerformConversions::class);
    }

    public function test_guest_can_not_store_a_picture_to_an_album(): void
    {
        Queue::fake();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album, $image);

        $this->assertSame(0, $album->fresh()->media->count());
        $response->assertStatus(401);
        Queue::assertNotPushed(PerformConversions::class);
    }

    public function storeAlbumPicture(Album $album, $media, array $data = []): TestResponse
    {
        session()->setPreviousUrl('/admin/albums/create');

        return $this->json('post', '/api/admin/album-pictures',
            array_merge([
                'album_slug' => $album->slug, 'file' => $media,
            ], $data));
    }
}
