<?php

namespace Tests\Feature\Http\Controller\Api\AdminAlbum;

use App\Models\Album;
use App\Models\Category;
use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class StoreAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_an_album_without_a_picture(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->media->first);
        $response->assertCreated();
    }

    public function storeAlbum(Album $album, array $optional = []): TestResponse
    {
        session()->setPreviousUrl('/admin/albums/create');

        return $this->json('post', '/api/admin/albums', array_merge(
            [
                'slug' => null,
                'title' => $album->title,
                'body' => $album->body,
                'published_at' => $album->published_at,
                'private' => $album->private,
                'meta_description' => $album->meta_description,
                'categories' => $album->categories->pluck('id'),
                'cosplayers' => $album->cosplayers->pluck('id'),
            ], $optional));
    }

    public function test_admin_can_store_an_album_with_a_picture(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->media->first);
        $response->assertCreated();
    }

    public function test_admin_can_store_an_album_with_a_category_and_a_picture(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();
        $category = factory(Category::class)->create();

        $response = $this->storeAlbum($album, [
            'categories' => [['id' => $category->id]],
        ]);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->categories->first);
        $response->assertCreated();
    }

    public function test_admin_can_not_store_an_album_with_an_non_existent_category_and_a_picture(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album, [
            'categories' => [['id' => 42]],
        ]);

        $this->assertSame(0, Album::count());
        $response->assertStatus(422);
    }

    public function test_admin_can_store_an_album_with_a_cosplayer_and_a_picture(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->storeAlbum($album, [
            'cosplayers' => [['id' => $cosplayer->id]],
        ]);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->categories->first);
        $response->assertCreated();
    }

    public function test_admin_can_store_an_album_with_an_non_existent_cosplayer(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album, [
            'cosplayers' => [['id' => 42]],
        ]);

        $this->assertSame(0, Album::count());
        $response->assertStatus(422);
    }

    public function test_admin_can_store_an_album_with_a_multiple_pictures(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertCreated();
    }

    public function test_admin_can_store_an_album_with_published_now(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertCreated();
    }

    public function test_admin_can_store_an_album_with_private_to_null_will_default_true(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->make();

        $response = $this->storeAlbum($album, ['private' => null]);

        $this->assertSame(1, Album::count());
        $response->assertCreated()
            ->assertJsonPath('data.private', true);
    }

    public function test_user_cannot_store_an_album(): void
    {
        $this->actingAsUser();
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(0, Album::count());
        $response->assertStatus(403);
    }

    public function test_guest_cannot_store_an_album(): void
    {
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album);

        $this->assertSame(0, Album::count());
        $response->assertStatus(401);
    }

    public function test_admin_cannot_add_cosplayer_to_same_album_twice(): void
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();
        /** @var Album $album */
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album, [
            'cosplayers' => [[
                'id' => $cosplayer->id,
            ], [
                'id' => $cosplayer->id,
            ]],
        ]);

        $this->assertSame(1, Album::count());
        $this->assertSame(1, Album::first()->cosplayers()->count());
        $response->assertCreated();
    }

    public function test_admin_send_category_twice_will_be_save_once(): void
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();
        /** @var Album $album */
        $album = factory(Album::class)->make();

        $response = $this->storeAlbum($album, [
            'categories' => [[
                'id' => $category->id,
            ], [
                'id' => $category->id,
            ]],
        ]);

        $this->assertSame(1, Album::count());
        $this->assertSame(1, Album::first()->categories()->count());
        $response->assertCreated();
    }
}
