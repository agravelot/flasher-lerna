<?php

namespace Tests\Unit\Models;

use App\Models\Album;
use App\Models\Picture;
use App\Models\User;
use Cocur\Slugify\Slugify;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class AlbumTest extends ModelTestCase
{
    use WithFaker;

    public function testRouteKeyName()
    {
        $post = new Album();
        $slug = $this->faker->slug;
        $routeKey = $post->getRouteKeyName();
        $post->$$routeKey = $slug;

        $this->assertEquals($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName()
    {
        $post = new Album();
        $routeKey = $post->getRouteKeyName();

        $excepted = "slug";

        $this->assertEquals($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle()
    {
        $post = new Album();
        $slugSource = $post->sluggable()['slug']['source'];

        $excepted = "title";

        $this->assertEquals($excepted, $slugSource);
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new Album(), [
            'title', 'slug', 'seo_title', 'excerpt', 'body', 'meta_description', 'meta_keywords', 'publish', 'user_id'
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

    public function test_post_is_public() {
        $post = factory(Album::class)->make([
            'publish' => true,
            'password' => null,
        ]);

        $this->assertEquals(true, $post->isPublic());
    }

    public function test_post_is_private_when_not_published() {
        $post = factory(Album::class)->make([
            'publish' => false,
            'password' => null,
        ]);

        $this->assertEquals(false, $post->isPublic());
    }

    public function test_post_is_private_when_password_is_defined() {
        $post = factory(Album::class)->make([
            'publish' => true,
            'password' => 'password',
        ]);

        $this->assertEquals(false, $post->isPublic());
    }

    public function test_post_is_private_when_password_is_defined_and_not_published() {
        $post = factory(Album::class)->make([
            'publish' => false,
            'password' => 'password',
        ]);

        $this->assertEquals(false, $post->isPublic());
    }
}
