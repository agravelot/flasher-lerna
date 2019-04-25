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

        $medias = $album->fresh()->getMedia('pictures');
        $this->assertSame(1, $medias->count());
        $this->assertNotSame($picture->getClientOriginalName(), $medias->first()->file_name);
        $this->assertStringContainsString($album->slug, $medias->first()->file_name);
        $response->assertStatus(201)
            ->assertJson([
                'path' => $album->fresh()->getFirstMediaUrl('pictures'),
                'name' => $medias->first()->file_name,
                'mime_type' => 'image/jpeg',
            ]);
    }

    public function test_admin_can_store_picture_twice_to_an_album()
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->state('published')->create();
        $picture = UploadedFile::fake()->image('fake.jpg');

        $response1 = $this->storeAlbumPicture($album->slug, $picture);
        $response2 = $this->storeAlbumPicture($album->slug, $picture);

        $medias = $album->fresh()->getMedia('pictures');
        $this->assertSame(2, $medias->count());
        $this->assertNotSame($picture->getClientOriginalName(), $medias->get(0)->file_name);
        $this->assertNotSame($picture->getClientOriginalName(), $medias->get(1)->file_name);
        $this->assertStringContainsString($album->slug, $medias->get(0)->file_name);
        $this->assertStringContainsString($album->slug, $medias->get(1)->file_name);
        $response1->assertStatus(201)
            ->assertJson([
                'path' => $album->fresh()->getMedia('pictures')->get(0)->getUrl(),
                'name' => $medias->get(0)->file_name,
                'mime_type' => 'image/jpeg',
            ]);
        $response2->assertStatus(201)
            ->assertJson([
                'path' => $album->fresh()->getMedia('pictures')->get(1)->getUrl(),
                'name' => $medias->get(1)->file_name,
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
