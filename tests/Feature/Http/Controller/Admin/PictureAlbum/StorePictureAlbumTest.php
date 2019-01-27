<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
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

        $response = $this->storeAlbumPicture($album, $picture);

        $this->assertSame(1, $album->fresh()->getMedia('pictures')->count());
        $this->assertSame($picture->getClientOriginalName(), $album->fresh()->getMedia('pictures')->first()->file_name);
        $response->assertStatus(200);
    }

    public function storeAlbumPicture(Album $album, UploadedFile $picture, array $optional = []): TestResponse
    {
        session()->setPreviousUrl('/admin/albums/create');

        return $this->post('/admin/album-pictures',
            array_merge(['picture' => $picture, 'album_slug' => $album->slug], $optional)
        );
    }

    public function test_admin_can_store_a_video_to_an_album()
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->state('published')->create();
        $picture = UploadedFile::fake()->image('fake.mp4');

        $response = $this->storeAlbumPicture($album, $picture);

        $this->assertSame(0, $album->fresh()->getMedia('pictures')->count());
        $response->assertStatus(302);
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee('The picture must be an image');
    }

    public function test_user_cannot_store_a_picture_to_an_album()
    {
        $this->actingAsUser();
        /** @var Album $album */
        $album = factory(Album::class)->state('published')->create();
        $picture = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album, $picture);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_store_a_picture_to_an_album_and_is_redirected_to_login()
    {
        /** @var Album $album */
        $album = factory(Album::class)->states(['published', 'withUser'])->create();
        $picture = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbumPicture($album, $picture);

        $response->assertRedirect('/login');
        $this->followRedirects($response)
            ->assertStatus(200);
    }
}
