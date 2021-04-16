<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\PublicAlbum;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class PublicAlbumTest extends ModelTestCase
{
    use WithFaker, RefreshDatabase;

    public function testRouteKeyName(): void
    {
        $album = new PublicAlbum();
        $slug = $this->faker->slug;
        $routeKey = $album->getRouteKeyName();
        $album->$$routeKey = $slug;

        $this->assertSame($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName(): void
    {
        $album = new PublicAlbum();
        $routeKey = $album->getRouteKeyName();

        $excepted = 'slug';

        $this->assertSame($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle(): void
    {
        $album = new PublicAlbum();
        $slugSource = $album->sluggable()['slug']['source'];

        $excepted = 'title';

        $this->assertSame($excepted, $slugSource);
    }

    public function testModelConfiguration(): void
    {
        $this->runConfigurationAssertions(new PublicAlbum(), [
            'title',
            'meta_description',
            'slug',
            'body',
            'published_at',
            'user_id',
            'private',
            'notify_users_on_published',
        ], [], ['*'], [], ['id' => 'int', 'private' => 'bool'], [
            'published_at',
            'updated_at',
            'created_at',
        ]);
    }

    public function testBelongsToManyPublicAlbumsRelationship(): void
    {
        $album = new PublicAlbum();
        $relation = $album->cosplayers();

        $this->assertBelongsToManyRelation($relation, $album, new PublicAlbum(), 'album_id');
    }

    public function test_album_is_public(): void
    {
        $album = factory(PublicAlbum::class)->states(['passwordLess', 'published'])->make();

        $this->assertTrue($album->isPublic());
    }

    public function test_album_without_password_is_passwordLess(): void
    {
        /** @var PublicAlbum $album */
        $album = factory(PublicAlbum::class)->state('passwordLess')->make();

        $this->assertTrue($album->isPasswordLess());
    }

    public function test_album_with_password_is_not_passwordLess(): void
    {
        /** @var PublicAlbum $album */
        $album = factory(PublicAlbum::class)->states(['password'])->make();

        $this->assertFalse($album->isPasswordLess());
    }

    public function test_set_published_at_with_true_is_now(): void
    {
        $knownDate = Carbon::create(2018, 5, 21, 12);
        Carbon::setTestNow($knownDate);
        $album = factory(PublicAlbum::class)->make();

        $this->assertSame($knownDate->format('Y-m-d H:i:s'), $album->published_at->format('Y-m-d H:i:s'));
    }

    public function test_set_published_at_with_date(): void
    {
        $knownDate = Carbon::create(2018, 5, 21, 12);
        Carbon::setTestNow($knownDate);
        $album = factory(PublicAlbum::class)->make();
        $album->published_at = $knownDate;

        $this->assertSame($knownDate->format('Y-m-d H:i:s'), $album->published_at->format('Y-m-d H:i:s'));
    }

    public function test_album_with_a_published_at_date_are_published(): void
    {
        $publishedPublicAlbums = factory(PublicAlbum::class, 2)->states(['published'])->create();

        $albums = PublicAlbum::all();

        $this->assertSame(2, $albums->count());
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(1)));
    }

    public function test_album_with_unpublished_are_not_visible(): void
    {
        factory(PublicAlbum::class, 2)->states(['unpublished', 'passwordLess'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->isEmpty());
    }

    public function test_album_with_a_password_are_not_visible(): void
    {
        factory(PublicAlbum::class, 2)->states(['published', 'password'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->isEmpty());
    }

    public function test_album_with_a_published_at_date_and_password_are_unpublished(): void
    {
        factory(PublicAlbum::class, 2)->states(['published', 'password'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->isEmpty());
    }

    public function test_count_four_published_albums_should_be_four(): void
    {
        $publishedPublicAlbums = factory(PublicAlbum::class, 4)->states([
            'published', 'passwordLess',
        ])->create();

        $albums = PublicAlbum::all();

        $this->assertSame(4, $albums->count());
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(1)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(2)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(3)));
    }

    public function test_count_four_public_albums_should_be_zero(): void
    {
        factory(PublicAlbum::class, 4)->states(['unpublished', 'passwordLess'])->create();

        $all = PublicAlbum::all();

        $this->assertTrue($all->isEmpty());
    }

    public function test_album_with_a_published_at_date_are_published_without_public_criteria(): void
    {
        $publishedPublicAlbums = factory(PublicAlbum::class, 2)->states(['published'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->contains($publishedPublicAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(1)));
    }

    public function test_album_with_password_are_not_public(): void
    {
        factory(PublicAlbum::class, 2)->states(['unpublished'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->isEmpty());
    }

    public function test_album_with_a_published_at_date_and_password_are_unpublished_without_public_criteria(): void
    {
        factory(PublicAlbum::class, 2)->states(['published', 'password'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->isEmpty());
    }

    public function test_count_four_published_albums_should_be_four_without_public_criteria(): void
    {
        $publishedPublicAlbums = factory(PublicAlbum::class, 4)->states([
            'published', 'passwordLess',
        ])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->contains($publishedPublicAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(1)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(2)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(3)));
    }

    public function test_count_four_unpublished_albums_should_be_zero_without_public_criteria(): void
    {
        factory(PublicAlbum::class, 4)->states(['unpublished', 'passwordLess'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->isEmpty());
    }
}
