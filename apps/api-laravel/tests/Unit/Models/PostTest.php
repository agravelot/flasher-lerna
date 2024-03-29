<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class PostTest extends ModelTestCase
{
    use WithFaker;

    public function testRouteKeyName(): void
    {
        $slug = $this->faker->slug;
        $post = factory(Post::class)->make([
            'slug' => $slug,
        ]);

        $routeKey = $post->getRouteKeyName();
        $post->$$routeKey = $slug;

        $this->assertSame($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName(): void
    {
        $post = new Post();
        $routeKey = $post->getRouteKeyName();

        $excepted = 'slug';

        $this->assertSame($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle(): void
    {
        $post = new Post();
        $slugSource = $post->sluggable()['slug']['source'];

        $excepted = 'title';

        $this->assertSame($excepted, $slugSource);
    }

    public function testModelConfiguration(): void
    {
        $this->runConfigurationAssertions(new Post(), [
            'title', 'slug', 'seo_title', 'excerpt', 'body', 'meta_description', 'meta_keywords', 'active', 'user_id',
        ]);
    }
}
