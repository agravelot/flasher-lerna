<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Tests\Feature\Http\Controller\Api\AdminAlbum;

use App\Models\Album;
use App\Models\Category;
use App\Models\Cosplayer;
use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Collection;
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
        return $this->json('patch', '/api/admin/albums/' . $album->slug, array_merge($album->toArray(), $optional));
    }

    public function test_admin_can_update_an_album()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();

        $response = $this->updateAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertStatus(200);
    }

    public function test_admin_can_update_an_album_with_a_new_category()
    {
        $this->disableExceptionHandling();
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $category = factory(Category::class)->create();
        $category->save();

        $response = $this->updateAlbum($album, [
            'categories' => [['id' => $category->id]],
        ]);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->categories->first);
        $response->assertStatus(200);
    }

    public function test_admin_can_update_an_album_with_an_non_existent_category_and_a_picture()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();

        $response = $this->updateAlbum($album, [
            'categories' => [['id' => 42]],
        ]);

        $this->assertSame(1, Album::count());
        $response->assertStatus(422);
    }

    public function test_admin_can_update_an_album_with_a_cosplayer()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->updateAlbum($album, [
            'cosplayers' => [['id' => $cosplayer->id]],
        ]);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->categories->first);
        $response->assertStatus(200);
    }

    public function test_admin_can_not_update_an_album_with_an_non_existent_cosplayer()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();

        $response = $this->updateAlbum($album, [
            'cosplayers' => [['id' => 42]],
        ]);

        $this->assertSame(1, Album::count());
        $response->assertStatus(422);
    }

    public function test_admin_can_update_an_album_with_published_now()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->create();

        $response = $this->updateAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertStatus(200);
    }

    public function test_user_cannot_update_an_album()
    {
        $this->actingAsUser();
        $album = factory(Album::class)->create();

        $response = $this->updateAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertStatus(403);
    }

    public function test_guest_cannot_update_an_album()
    {
        $album = factory(Album::class)->state('withUser')->create();

        $response = $this->updateAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertStatus(401);
    }

    public function test_cosplayer_are_not_declared_twice_after_update()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $cosplayer = factory(Cosplayer::class)->create();
        $album->cosplayers()->save($cosplayer);

        $response = $this->updateAlbum($album, [
            'cosplayers' => [['id' => $cosplayer->id]],
        ]);

        $this->assertSame(1, $album->fresh()->cosplayers->count());
    }

    public function test_category_are_not_declared_twice_after_update()
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $category = factory(Category::class)->create();
        $album->categories()->save($category);

        $response = $this->updateAlbum($album, [
            'categories' => [['id' => $category->id]],
        ]);

        $this->assertSame(1, $album->fresh()->categories->count());
    }

    public function test_album_can_be_updated_with_a_new_published_at_date()
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->create();

        $response = $this->updateAlbum($album, [
            'published_at' => Carbon::now()->format(DateTime::ATOM),
        ]);

        $this->assertSame(1, Album::count());
        $response->assertStatus(200);
    }

    public function test_album_with_categories_can_un_attach()
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->create();
        $category = factory(Category::class)->create();
        $album->categories()->save($category);
        $this->assertCount(1, $album->categories);

        $response = $this->updateAlbum($album, [
            'categories' => [],
        ]);

        $this->assertCount(0, $album->fresh()->categories);
        $this->assertSame(1, Album::count());
        $response->assertStatus(200);
    }

    public function test_album_with_multiple_categories_can_un_attach_one()
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->create();
        /** @var Collection $categories */
        $categories = factory(Category::class, 5)->create();
        $album->categories()->attach($categories);
        $this->assertCount(5, $album->categories);

        $categories->shift();
        $response = $this->updateAlbum($album, [
            'categories' => $categories,
        ]);

        $this->assertCount(4, $album->fresh()->categories);
        $this->assertSame(1, Album::count());
        $response->assertStatus(200);
    }
}
