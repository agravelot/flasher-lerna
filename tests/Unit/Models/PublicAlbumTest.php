<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Unit\Models;

use App\Models\PublicAlbum;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class PublicAlbumTest extends ModelTestCase
{
    use WithFaker, RefreshDatabase;

    public function testRouteKeyName()
    {
        $album = new PublicAlbum();
        $slug = $this->faker->slug;
        $routeKey = $album->getRouteKeyName();
        $album->$$routeKey = $slug;

        $this->assertSame($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName()
    {
        $album = new PublicAlbum();
        $routeKey = $album->getRouteKeyName();

        $excepted = 'slug';

        $this->assertSame($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle()
    {
        $album = new PublicAlbum();
        $slugSource = $album->sluggable()['slug']['source'];

        $excepted = 'title';

        $this->assertSame($excepted, $slugSource);
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new PublicAlbum(), [
            'title', 'slug', 'seo_title', 'excerpt', 'body', 'meta_description', 'meta_keywords', 'published_at', 'user_id', 'private',
        ]);
    }

    public function testBelongsToManyPublicAlbumsRelationship()
    {
        $album = new PublicAlbum();
        $relation = $album->cosplayers();

        $this->assertBelongsToManyRelation($relation, $album, new PublicAlbum(), 'album_id');
    }

    public function test_album_is_public()
    {
        $album = factory(PublicAlbum::class)->states(['passwordLess', 'published'])->make();

        $this->assertTrue($album->isPublic());
    }

    public function test_album_without_password_is_passwordLess()
    {
        /** @var PublicAlbum $album */
        $album = factory(PublicAlbum::class)->state('passwordLess')->make();

        $this->assertTrue($album->isPasswordLess());
    }

    public function test_album_with_password_is_not_passwordLess()
    {
        /** @var PublicAlbum $album */
        $album = factory(PublicAlbum::class)->states(['password'])->make();

        $this->assertFalse($album->isPasswordLess());
    }

    public function test_set_published_at_with_true_is_now()
    {
        $knownDate = Carbon::create(2018, 5, 21, 12);
        Carbon::setTestNow($knownDate);
        $album = factory(PublicAlbum::class)->make();

        $this->assertSame($knownDate->format('Y-m-d H:i:s'), $album->published_at->format('Y-m-d H:i:s'));
    }

    public function test_set_published_at_with_date()
    {
        $knownDate = Carbon::create(2018, 5, 21, 12);
        Carbon::setTestNow($knownDate);
        $album = factory(PublicAlbum::class)->make();
        $album->published_at = $knownDate;

        $this->assertSame($knownDate->format('Y-m-d H:i:s'), $album->published_at->format('Y-m-d H:i:s'));
    }

    public function test_album_with_a_published_at_date_are_published()
    {
        $publishedPublicAlbums = factory(PublicAlbum::class, 2)->states(['published', 'withUser'])->create();

        $albums = PublicAlbum::all();

        $this->assertSame(2, $albums->count());
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(1)));
    }

    public function test_album_with_unpublished_are_not_visible()
    {
        factory(PublicAlbum::class, 2)->states(['unpublished', 'passwordLess', 'withUser'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->isEmpty());
    }

    public function test_album_with_a_password_are_not_visible()
    {
        factory(PublicAlbum::class, 2)->states(['published', 'password', 'withUser'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->isEmpty());
    }

    public function test_album_with_a_published_at_date_and_password_are_unpublished()
    {
        factory(PublicAlbum::class, 2)->states(['published', 'password', 'withUser'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->isEmpty());
    }

    public function test_count_four_published_albums_should_be_four()
    {
        $publishedPublicAlbums = factory(PublicAlbum::class, 4)->states(['published', 'passwordLess', 'withUser'])->create();

        $albums = PublicAlbum::all();

        $this->assertSame(4, $albums->count());
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(1)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(2)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(3)));
    }

    public function test_count_four_public_albums_should_be_zero()
    {
        factory(PublicAlbum::class, 4)->states(['unpublished', 'passwordLess', 'withUser'])->create();

        $all = PublicAlbum::all();

        $this->assertTrue($all->isEmpty());
    }

    public function test_album_with_a_published_at_date_are_published_without_public_criteria()
    {
        $publishedPublicAlbums = factory(PublicAlbum::class, 2)->states(['published', 'withUser'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->contains($publishedPublicAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(1)));
    }

    public function test_album_with_password_are_not_public()
    {
        factory(PublicAlbum::class, 2)->states(['unpublished', 'withUser'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->isEmpty());
    }

    public function test_album_with_a_published_at_date_and_password_are_unpublished_without_public_criteria()
    {
        factory(PublicAlbum::class, 2)->states(['published', 'password', 'withUser'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->isEmpty());
    }

    public function test_count_four_published_albums_should_be_four_without_public_criteria()
    {
        $publishedPublicAlbums = factory(PublicAlbum::class, 4)->states(['published', 'passwordLess', 'withUser'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->contains($publishedPublicAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(1)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(2)));
        $this->assertTrue($albums->contains($publishedPublicAlbums->get(3)));
    }

    public function test_count_four_unpublished_albums_should_be_zero_without_public_criteria()
    {
        factory(PublicAlbum::class, 4)->states(['unpublished', 'passwordLess', 'withUser'])->create();

        $albums = PublicAlbum::all();

        $this->assertTrue($albums->isEmpty());
    }
}
