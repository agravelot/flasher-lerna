<?php

namespace Tests\Unit\Models;

use App\Models\Album;
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

        $this->assertEquals($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName()
    {
        $album = new Album();
        $routeKey = $album->getRouteKeyName();

        $excepted = 'slug';

        $this->assertEquals($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle()
    {
        $album = new Album();
        $slugSource = $album->sluggable()['slug']['source'];

        $excepted = 'title';

        $this->assertEquals($excepted, $slugSource);
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new Album(), [
            'title', 'slug', 'seo_title', 'excerpt', 'body', 'meta_description', 'meta_keywords', 'published_at', 'user_id',
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

        $this->assertEquals(true, $album->isPublic());
    }

    public function test_album_is_private_when_not_published()
    {
        $album = factory(Album::class)->states(['unpublished', 'passwordLess'])->make();

        $this->assertEquals(false, $album->isPublic());
    }

    public function test_album_is_private_when_password_is_defined()
    {
        $album = factory(Album::class)->states(['published', 'password'])->make();

        $this->assertEquals(false, $album->isPublic());
    }

    public function test_album_is_private_when_password_is_defined_and_not_published()
    {
        $album = factory(Album::class)->states(['unpublished', 'password'])->make();

        $this->assertEquals(false, $album->isPublic());
    }

    public function test_password_should_be_hashed()
    {
        $album = factory(Album::class)->make([
            'password' => 'secret',
        ]);

        $this->assertTrue(Hash::check('secret', $album->password));
    }
}
