<?php

namespace Tests\Feature\Http\Controller\Api\AdminPictureAlbum;

use Tests\TestCase;
use App\Models\Album;
use App\Jobs\PerformConversions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreAdminPictureAlbum extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_a_picture_to_an_album()
    {
        Queue::fake();
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');
        Queue::assertNothingPushed();

        $response = $this->storeAlbumPicture($album, $image);

        $this->assertSame(1, $album->fresh()->media->count());
        $response->assertStatus(201);
        Queue::assertPushedOn('images', PerformConversions::class);
    }

    public function test_admin_can_not_store_a_video_to_an_album()
    {
        Queue::fake();
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $video = UploadedFile::fake()->image('fake.mp4');

        $response = $this->storeAlbumPicture($album, $video);

        $this->assertSame(0, $album->fresh()->media->count());
        $response->assertStatus(422);
        Queue::assertNothingPushed();
    }

    public function test_user_can_not_store_a_picture_to_an_album()
    {
        Queue::fake();
        $this->actingAsUser();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album, $image);

        $this->assertSame(0, $album->fresh()->media->count());
        $response->assertStatus(403);
        Queue::assertNothingPushed();
    }

    public function test_guest_can_not_store_a_picture_to_an_album()
    {
        Queue::fake();
        $album = factory(Album::class)->state('withUser')->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album, $image);

        $this->assertSame(0, $album->fresh()->media->count());
        $response->assertStatus(401);
        Queue::assertNothingPushed();
    }

    public function test_uploaded_image_path_is_relative()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('withUser')->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album, $image);

        $this->assertSame('/storage/1/fake.jpg', $album->fresh()->media->get(0)->getUrl());
    }

    public function storeAlbumPicture(Album $album, $media, array $optional = []): TestResponse
    {
        session()->setPreviousUrl('/admin/albums/create');

        return $this->json('post', '/api/admin/album-pictures',
            array_merge(['album_slug' => $album->slug, 'file' => $media], $optional));
    }
}
