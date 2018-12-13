<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class PostTest extends ModelTestCase
{
    use WithFaker;

    public function testRouteKeyName()
    {
        $slug = $this->faker->slug;
        $post = factory(Post::class)->make([
            'slug' => $slug,
        ]);

        $routeKey = $post->getRouteKeyName();
        $post->$$routeKey = $slug;

        $this->assertEquals($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName()
    {
        $post = new Post();
        $routeKey = $post->getRouteKeyName();

        $excepted = "slug";

        $this->assertEquals($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle()
    {
        $post = new Post();
        $slugSource = $post->sluggable()['slug']['source'];

        $excepted = "title";

        $this->assertEquals($excepted, $slugSource);
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new Post(), [
            'title', 'slug', 'seo_title', 'excerpt', 'body', 'meta_description', 'meta_keywords', 'active', 'user_id'
        ]);
    }
}
