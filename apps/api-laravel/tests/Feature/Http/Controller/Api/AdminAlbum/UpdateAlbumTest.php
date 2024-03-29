<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\AdminAlbum;

use App\Models\Album;
use App\Models\Category;
use App\Models\Cosplayer;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_an_album(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();

        $response = $this->updateAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertOk();
    }

    public function updateAlbum(Album $album, array $optional = []): TestResponse
    {
        return $this->json('patch', "/api/admin/albums/{$album->slug}", array_merge([
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

    public function test_admin_can_update_an_album_with_a_new_category(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $category = factory(Category::class)->create();
        $category->save();

        $response = $this->updateAlbum($album, [
            'categories' => [['id' => $category->id]],
        ]);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->categories->first);
        $response->assertOk()
            ->assertJson(['data' => [
                'categories' => [
                    ['id' => $category->id],
                ],
            ]]);
    }

    public function test_admin_can_update_an_album_with_an_non_existent_category_and_a_picture(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();

        $response = $this->updateAlbum($album, [
            'categories' => [['id' => 42]],
        ]);

        $this->assertSame(1, Album::count());
        $response->assertStatus(422);
    }

    public function test_admin_can_update_an_album_with_a_cosplayer(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->updateAlbum($album, [
            'cosplayers' => [['id' => $cosplayer->id]],
        ]);

        $this->assertSame(1, Album::count());
        $this->assertNotNull(Album::first()->categories->first);
        $response->assertOk();
    }

    public function test_admin_can_not_update_an_album_with_an_non_existent_cosplayer(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();

        $response = $this->updateAlbum($album, [
            'cosplayers' => [['id' => 42]],
        ]);

        $this->assertSame(1, Album::count());
        $response->assertStatus(422);
    }

    public function test_admin_can_update_an_album_with_published_now(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->state('published')->create();

        $response = $this->updateAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertOk();
    }

    public function test_user_cannot_update_an_album(): void
    {
        $this->actingAsUser();
        $album = factory(Album::class)->create();

        $response = $this->updateAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertStatus(403);
    }

    public function test_guest_cannot_update_an_album(): void
    {
        $album = factory(Album::class)->create();

        $response = $this->updateAlbum($album);

        $this->assertSame(1, Album::count());
        $response->assertStatus(401);
    }

    public function test_cosplayer_are_not_saved_twice_after_update(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $cosplayer = factory(Cosplayer::class)->create();
        $album->cosplayers()->save($cosplayer);

        $response = $this->updateAlbum($album, [
            'cosplayers' => [['id' => $cosplayer->id]],
        ]);

        $response->assertOk();
        $this->assertSame(1, $album->fresh()->cosplayers->count());
    }

    public function test_category_are_not_saved_twice_after_update(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $category = factory(Category::class)->create();
        $album->categories()->save($category);

        $response = $this->updateAlbum($album, [
            'categories' => [['id' => $category->id]],
        ]);

        $response->assertOk();
        $this->assertSame(1, $album->fresh()->categories->count());
    }

    public function test_album_can_be_updated_with_a_new_published_at_date(): void
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->create();

        $response = $this->updateAlbum($album, [
            'published_at' => (new DateTime())->format(DateTime::ISO8601),
        ]);

        $this->assertSame(1, Album::count());
        $response->assertOk();
    }

    public function test_album_with_categories_can_un_attach(): void
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
        $response->assertOk();
    }

    public function test_album_with_multiple_categories_can_un_attach_one(): void
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
        $response->assertOk();
    }

    public function test_album_update_a_published_album_to_unpublished(): void
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->state('published')->create();

        $this->assertTrue($album->isPublished());
        $response = $this->updateAlbum($album, [
            'published_at' => null,
        ]);

        $this->assertFalse($album->fresh()->isPublished());
    }

    public function test_album_update_an_unpublished_album_to_published(): void
    {
        $this->actingAsAdmin();
        /** @var Album $album */
        $album = factory(Album::class)->state('unpublished')->create();

        $this->assertFalse($album->isPublished());
        $response = $this->updateAlbum($album, [
            'published_at' => (new \DateTime())->format(DateTime::ISO8601),
        ]);

        $this->assertTrue($album->fresh()->isPublished());
    }

    public function test_admin_can_update_title_and_update_slug(): void
    {
        $this->actingAsAdmin();
        $album = factory(Album::class)->create();
        $album->title = $title = 'Title test';

        $response = $this->updateAlbum($album);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $album->id,
                    'slug' => Str::slug($album->title),
                    'title' => $album->title,
                    'meta_description' => $album->meta_description,
                    'published_at' => optional($album->published_at)->jsonSerialize(),
                    'created_at' => $album->created_at->jsonSerialize(),
//                    'body' => $album->body,
                    'private' => $album->private,
//                    'medias' => $album->medias,
//                    'categories' => $album->categories,
//                    'cosplayers' => $album->cosplayers,
//                    'user' => [
//                        'name' => $album->user->name
//                    ],
                ],
            ]);
        $this->assertSame($title, $album->fresh()->title);
        $this->assertSame(Str::slug($title), $album->fresh()->slug);
    }
}
