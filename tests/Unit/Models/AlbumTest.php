<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Unit\Models;

use App\Models\Album;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\ModelTestCase;

class AlbumTest extends ModelTestCase
{
    use WithFaker;

    public function testRouteKeyName()
    {
        $album = new Album();
        $slug = $this->faker->slug;
        $routeKey = $album->getRouteKeyName();
        $album->$$routeKey = $slug;

        $this->assertSame($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName()
    {
        $album = new Album();
        $routeKey = $album->getRouteKeyName();

        $excepted = 'slug';

        $this->assertSame($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle()
    {
        $album = new Album();
        $slugSource = $album->sluggable()['slug']['source'];

        $excepted = 'title';

        $this->assertSame($excepted, $slugSource);
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new Album(), [
            'title', 'slug', 'seo_title', 'excerpt', 'body', 'meta_description', 'meta_keywords', 'published_at', 'user_id', 'password',
        ],
            ['password']);
    }

    public function testBelongsToManyAlbumsRelationship()
    {
        $album = new Album();
        $relation = $album->cosplayers();

        $this->assertBelongsToManyRelation($relation, $album, new Album(), 'album_id');
    }

    //TODO add test morphToMany
//    public function testHasManyCategoriesRelationship()
//    {
//        $album = new Album();
//        $relation = $album->categories();
//
//        $this->assertHasManyRelation($relation, $album, new Category(), 'user_id');
//    }

    public function test_album_is_public()
    {
        $album = factory(Album::class)->states(['published', 'passwordLess'])->make();

        $this->assertTrue($album->isPublic());
    }

    public function test_album_is_private_when_not_published()
    {
        $album = factory(Album::class)->states(['unpublished', 'passwordLess'])->make();

        $this->assertFalse($album->isPublic());
    }

    public function test_album_is_private_when_password_is_defined()
    {
        $album = factory(Album::class)->states(['published', 'password'])->make();

        $this->assertFalse($album->isPublic());
    }

    public function test_album_is_private_when_password_is_defined_and_not_published()
    {
        $album = factory(Album::class)->states(['unpublished', 'password'])->make();

        $this->assertFalse($album->isPublic());
    }

    public function test_password_should_be_hashed()
    {
        $album = factory(Album::class)->make([
            'password' => 'secret',
        ]);

        $this->assertTrue(Hash::check('secret', $album->password));
    }

    public function test_album_without_password_is_passwordLess()
    {
        /** @var Album $album */
        $album = factory(Album::class)->states(['passwordLess'])->make();

        $this->assertTrue($album->isPasswordLess());
    }

    public function test_album_with_password_is_not_passwordLess()
    {
        /** @var Album $album */
        $album = factory(Album::class)->states(['password'])->make();

        $this->assertFalse($album->isPasswordLess());
    }

    public function test_set_published_at_with_true_is_now()
    {
        $knownDate = Carbon::create(2018, 5, 21, 12);
        Carbon::setTestNow($knownDate);
        $album = factory(Album::class)->states(['published'])->make();

        $this->assertSame($knownDate, $album->published_at);
    }

    public function test_set_published_at_with_false_is_null()
    {
        $album = factory(Album::class)->make();
        $album->published_at = false;

        $this->assertNull($album->published_at);
    }

    public function test_set_published_at_with_date()
    {
        $knownDate = Carbon::create(2018, 5, 21, 12);
        $album = factory(Album::class)->make();
        $album->published_at = $knownDate;

        $this->assertSame($knownDate, $album->published_at);
    }
}
