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

class StoreAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_not_store_an_album_without_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album);
        $this->followRedirects($response)->dump();

        $this->assertSame(0, Album::count());
        $response->assertRedirect('/admin/albums/create');
    }

    public function storeAlbum(Album $album, array $optional = []): TestResponse
    {
        session()->setPreviousUrl('/admin/albums/create');

        return $this->post('/admin/albums', array_merge($album->toArray(), $optional));
    }

    public function test_admin_can_store_an_album_with_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbum($album, ['pictures' => array_wrap($image)]);

        $this->assertSame(1, Album::count());
        $response->assertRedirect('/admin/albums');
    }

    public function test_admin_can_store_an_album_with_published_now()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->make();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbum($album, ['pictures' => array_wrap($image)]);

        $this->assertSame(1, Album::count());
        $response->assertRedirect('/admin/albums');
    }

    public function test_user_cannot_store_an_album()
    {
        $this->actingAsUser();
        $album = factory(Album::class)->make();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->storeAlbum($album, ['pictures' => array_wrap($image)]);

        $this->assertSame(0, Album::count());
        $response->assertStatus(403);
    }

    public function test_guest_cannot_store_an_album()
    {
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(0, Album::count());
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }
}
