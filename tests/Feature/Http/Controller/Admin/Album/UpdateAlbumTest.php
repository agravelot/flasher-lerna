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

class UpdateAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_an_album_without_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();

        $response = $this->updateAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertRedirect('/admin/albums');
        $this->followRedirects($response)
            ->assertSee('Album successfully updated');
    }

    public function updateAlbum(Album $album, array $optional = []): TestResponse
    {
        session()->setPreviousUrl('/admin/albums/' . $album->slug . '/edit');

        return $this->patch('/admin/albums/' . $album->slug, array_merge($album->toArray(), $optional));
    }

    public function test_admin_can_update_an_album_with_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->updateAlbum($album, ['pictures' => array_wrap($image)]);

        $this->assertSame(1, Album::count());
        $response->assertRedirect('/admin/albums');
        $this->followRedirects($response)
            ->assertSee($album->title)
            ->assertSee('Album successfully updated');
    }

    public function test_admin_can_update_an_album_with_a_multiple_pictures()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $images = collect()
            ->push(UploadedFile::fake()->image('fake.jpg'))
            ->push(UploadedFile::fake()->image('fake.jpg'));

        $response = $this->updateAlbum($album, ['pictures' => array_wrap($images->toArray())]);

        $this->assertSame(1, Album::count());
        $response->assertRedirect('/admin/albums');
        $this->followRedirects($response)
            ->assertSee($album->title)
            ->assertSee('Album successfully updated');
    }

    public function test_admin_can_not_update_an_album_with_a_video()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.mp4');

        $response = $this->updateAlbum($album, ['pictures' => array_wrap($image)]);

        $this->assertSame(1, Album::count());
        $response->assertRedirect('/admin/albums/' . $album->slug . '/edit');
        $this->followRedirects($response)
            ->assertSee('The pictures.0 must be an image.');
    }

    public function test_admin_can_update_an_album_with_published_now()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->updateAlbum($album, ['pictures' => array_wrap($image)]);

        $this->assertSame(1, Album::count());
        $response->assertRedirect('/admin/albums');
        $this->followRedirects($response)
            ->assertSee($album->title)
            ->assertSee('Album successfully updated');
    }

    public function test_user_cannot_update_an_album()
    {
        $this->actingAsUser();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->updateAlbum($album, ['pictures' => array_wrap($image)]);

        $this->assertSame(1, Album::count());
        $response->assertStatus(403);
    }

    public function test_guest_cannot_update_an_album()
    {
        $album = factory(Album::class)->state('withUser')->create();

        $response = $this->updateAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }
}
