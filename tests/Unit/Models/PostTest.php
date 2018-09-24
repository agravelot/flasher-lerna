<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class PostTest extends TestCase
{
    use WithFaker;

    public function testRouteKeyName()
    {
        $post = new Post();
        $slug = $this->faker->slug;
        $routeKey = $post->getRouteKeyName();
        $post->$$routeKey = $slug;

        $this->assertEquals($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName() {
        $post = new Post();
        $routeKey = $post->getRouteKeyName();

        $excepted = "slug";

        $this->assertEquals($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle() {
        $post = new Post();
        $slugSource = $post->sluggable()['slug']['source'];

        $excepted = "title";

        $this->assertEquals($excepted, $slugSource);
    }
}
