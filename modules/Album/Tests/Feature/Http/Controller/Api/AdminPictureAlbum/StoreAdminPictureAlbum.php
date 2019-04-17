<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Tests\Feature\Http\Controller\Api\AdminPictureAlbum;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StoreAdminPictureAlbum extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_a_picture_to_an_album()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album, $image);

        $this->assertSame(1, $album->fresh()->media->count());
        $response->assertStatus(201);
    }

    public function test_admin_can_not_store_a_video_to_an_album()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $video = UploadedFile::fake()->image('fake.mp4');

        $response = $this->storeAlbumPicture($album, $video);

        $this->assertSame(0, $album->fresh()->media->count());
        $response->assertStatus(422);
    }

    public function test_user_can_not_store_a_picture_to_an_album()
    {
        $this->actingAsUser();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album, $image);

        $this->assertSame(0, $album->fresh()->media->count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_store_a_picture_to_an_album()
    {
        $album = factory(Album::class)->state('withUser')->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album, $image);

        $this->assertSame(0, $album->fresh()->media->count());
        $response->assertStatus(401);
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

        return $this->json('post', '/api/admin/album-pictures', array_merge(['album_slug' => $album->slug, 'file' => $media], $optional));
    }
}
