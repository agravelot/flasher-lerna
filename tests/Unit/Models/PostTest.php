<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Unit\Models;

use App\Models\Post;
use Tests\ModelTestCase;
use Illuminate\Foundation\Testing\WithFaker;

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

        $this->assertSame($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName()
    {
        $post = new Post();
        $routeKey = $post->getRouteKeyName();

        $excepted = 'slug';

        $this->assertSame($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle()
    {
        $post = new Post();
        $slugSource = $post->sluggable()['slug']['source'];

        $excepted = 'title';

        $this->assertSame($excepted, $slugSource);
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new Post(), [
            'title', 'slug', 'seo_title', 'excerpt', 'body', 'meta_description', 'meta_keywords', 'active', 'user_id',
        ]);
    }
}
