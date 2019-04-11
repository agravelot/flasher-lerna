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
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StorePictureAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_a_picture_to_an_album()
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->state('published')->create();
        $picture = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album->slug, $picture);

        $this->assertSame(1, $album->fresh()->getMedia('pictures')->count());
        $this->assertSame($picture->getClientOriginalName(), $album->fresh()->getMedia('pictures')->first()->file_name);
        $response->assertStatus(201)
            ->assertJson([
                'path' => $album->fresh()->getFirstMediaUrl('pictures'),
                'name' => 'fake.jpg',
                'mime_type' => 'image/jpeg',
            ]);
    }

    public function storeAlbumPicture(string $albumSlug, UploadedFile $picture = null, array $optional = []): TestResponse
    {
        session()->setPreviousUrl('/admin/albums/create');

        return $this->json('post', '/api/admin/album-pictures',
            array_merge(['file' => $picture, 'album_slug' => $albumSlug], $optional)
        );
    }

    public function test_admin_can_not_store_a_picture_to_an_non_existent_album()
    {
        $this->actingAsAdmin();
        $picture = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture('a-random-slug', $picture);

        $response->assertStatus(422);
    }

    public function test_admin_can_not_store_an_empty_picture_to_an_album()
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->state('published')->create();

        $response = $this->storeAlbumPicture($album->slug);

        $this->assertSame(0, $album->fresh()->getMedia('pictures')->count());
        $response->assertStatus(422);
    }

    public function test_admin_can_not_store_a_video_to_an_album()
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->state('published')->create();
        $picture = UploadedFile::fake()->image('fake.mp4');

        $response = $this->storeAlbumPicture($album->slug, $picture);

        $this->assertSame(0, $album->fresh()->getMedia('pictures')->count());
        $response->assertStatus(422);
    }

    public function test_user_cannot_store_a_picture_to_an_album()
    {
        $this->actingAsUser();
        /** @var Album $album */
        $album = factory(Album::class)->state('published')->create();
        $picture = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album->slug, $picture);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_store_a_picture_to_an_album_and_is_redirected_to_login()
    {
        /** @var Album $album */
        $album = factory(Album::class)->states(['published', 'withUser'])->create();
        $picture = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album->slug, $picture);

        $response->assertStatus(401);
    }
}
