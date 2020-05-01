<?php

namespace Tests\Unit\Models;

use App\Models\Cosplayer;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class CosplayerTest extends ModelTestCase
{
    use WithFaker;

    public function testRouteKeyName()
    {
        $post = new Cosplayer();
        $slug = $this->faker->slug;
        $routeKey = $post->getRouteKeyName();
        $post->$$routeKey = $slug;

        $this->assertSame($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName()
    {
        $post = new Cosplayer();
        $routeKey = $post->getRouteKeyName();

        $excepted = 'slug';

        $this->assertSame($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle()
    {
        $post = new Cosplayer();
        $slugSource = $post->sluggable()['slug']['source'];

        $excepted = 'name';

        $this->assertSame($excepted, $slugSource);
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new Cosplayer(), [
            'name', 'description', 'slug', 'user_id',
        ]);
    }

    public function test_cosplayer_with_a_Tom_as_name_will_have_uppercase_t_as_initial()
    {
        $cosplayer = new Cosplayer();
        $cosplayer->name = 'Tom';

        $this->assertSame('T', $cosplayer->initial);
    }

    public function test_cosplayer_with_a_tom_lowercase_as_name_will_have_uppercase_t_as_initial()
    {
        $cosplayer = new Cosplayer();
        $cosplayer->name = 'tom';

        $this->assertSame('T', $cosplayer->initial);
    }
}
