<?php

namespace Tests\Unit\Models;

use App\Models\Album;
use App\Models\Picture;
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

    public function testHasManyAlbumsRelationship()
    {
        $album = new Album();
        $relation = $album->cosplayers();

        $this->assertHasManyRelation($relation, $album, new Album(), 'album_id');
    }

    //TODO add test morphToMany
//    public function testHasManyCategoriesRelationship()
//    {
//        $album = new Album();
//        $relation = $album->categories();
//
//        $this->assertHasManyRelation($relation, $album, new Category(), 'user_id');
//    }
}
