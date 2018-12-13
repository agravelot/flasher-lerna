<?php

namespace Tests\Unit\Models;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\ModelTestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CosplayerTest extends ModelTestCase
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

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new Cosplayer(), [
            'name', 'description', 'slug'
        ]);
    }

    public function testHasOneUserRelationship()
    {
        $cosplayer = new Cosplayer();
        $relation = $cosplayer->user();

        $this->assertBelongsToRelation($relation, $cosplayer, new User(), 'user_id');
    }
}
