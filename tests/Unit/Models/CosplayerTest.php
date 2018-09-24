<?php

namespace Tests\Unit\Models;

use App\Models\Cosplayer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CosplayerTest extends TestCase
{
    use WithFaker;

    public function testRouteKeyName()
    {
        $post = new Cosplayer();
        $slug = $this->faker->slug;
        $routeKey = $post->getRouteKeyName();
        $post->$$routeKey = $slug;

        $this->assertEquals($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName() {
        $post = new Cosplayer();
        $routeKey = $post->getRouteKeyName();

        $excepted = "slug";

        $this->assertEquals($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle() {
        $post = new Cosplayer();
        $slugSource = $post->sluggable()['slug']['source'];

        $excepted = "name";

        $this->assertEquals($excepted, $slugSource);
    }
}
