<?php

namespace Tests\Unit\Models;

use App\Models\Album;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlbumTest extends TestCase
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

    public function testSlugAsRouteKeyName() {
        $post = new Album();
        $routeKey = $post->getRouteKeyName();

        $excepted = "slug";

        $this->assertEquals($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle() {
        $post = new Album();
        $slugSource = $post->sluggable()['slug']['source'];

        $excepted = "title";

        $this->assertEquals($excepted, $slugSource);
    }
}
