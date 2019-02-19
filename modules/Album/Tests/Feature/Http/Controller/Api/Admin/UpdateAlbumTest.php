<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Tests\Feature\Http\Controller\Api\Admin\Album;

use App\Models\Album;
use App\Models\Category;
use App\Models\Cosplayer;
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
        $response->assertStatus(200);
    }

    public function updateAlbum(Album $album, array $optional = []): TestResponse
    {
        return $this->patch('/api/admin/albums/' . $album->slug, array_merge($album->toArray(), $optional));
    }

    public function test_admin_can_update_an_album_with_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->updateAlbum($album, ['pictures' => array_wrap($image)]);

        $this->assertSame(1, Album::count());
        $response->assertStatus(200);
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
        $response->assertStatus(200);
    }

    public function test_admin_can_update_an_album_with_a_category_and_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $category = factory(Category::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->updateAlbum($album, [
            'categories' => array_wrap($category->id),
            'pictures' => array_wrap($image),
        ]);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->categories->first);
        $response->assertStatus(200);
    }

    public function test_admin_can_update_an_album_with_an_non_existent_category_and_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->updateAlbum($album, [
            'categories' => array_wrap(42),
            'pictures' => array_wrap($image),
        ]);

        $this->assertSame(1, Album::count());
        $response->assertStatus(422);
    }

    public function test_admin_can_update_an_album_with_a_cosplayer_and_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $cosplayer = factory(Cosplayer::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->updateAlbum($album, [
            'cosplayers' => array_wrap($cosplayer->id),
            'pictures' => array_wrap($image),
        ]);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->categories->first);
        $response->assertStatus(200);
    }

    public function test_admin_can_update_an_album_with_an_non_existent_cosplayer_and_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->updateAlbum($album, [
            'cosplayers' => array_wrap(42),
            'pictures' => array_wrap($image),
        ]);

        $this->assertSame(1, Album::count());
        $response->assertStatus(200);
    }

    public function test_admin_can_not_update_an_album_with_a_video()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $image = UploadedFile::fake()->image('fake.mp4');

        $response = $this->updateAlbum($album, ['pictures' => array_wrap($image)]);

        $this->assertSame(1, Album::count());
        $response->assertStatus(422);
    }

    public function test_admin_can_update_an_album_with_published_now()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->create();
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->updateAlbum($album, ['pictures' => array_wrap($image)]);

        $this->assertSame(1, Album::count());
        $response->assertStatus(200);
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

    public function test_cosplayer_are_not_declared_twice_after_update()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $cosplayer = factory(Cosplayer::class)->create();
        $album->cosplayers()->save($cosplayer);
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->updateAlbum($album, [
            'cosplayers' => array_wrap($cosplayer->id),
            'pictures' => array_wrap($image),
        ]);

        $this->assertSame(1, $album->fresh()->cosplayers->count());
    }

    public function test_category_are_not_declared_twice_after_update()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $category = factory(Category::class)->create();
        $album->categories()->save($category);
        $image = UploadedFile::fake()->image('fake.jpg');

        $response = $this->updateAlbum($album, [
            'categories' => array_wrap($category->id),
            'pictures' => array_wrap($image),
        ]);

        $this->assertSame(1, $album->fresh()->categories->count());
    }
}
