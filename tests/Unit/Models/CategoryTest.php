<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Unit\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class CategoryTest extends ModelTestCase
{
    use WithFaker;

    public function testRouteKeyName()
    {
        $post = new Category();
        $slug = $this->faker->slug;
        $routeKey = $post->getRouteKeyName();
        $post->$$routeKey = $slug;

        $this->assertSame($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName()
    {
        $post = new Category();
        $routeKey = $post->getRouteKeyName();

        $excepted = 'slug';

        $this->assertSame($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle()
    {
        $post = new Category();
        $slugSource = $post->sluggable()['slug']['source'];

        $excepted = 'name';

        $this->assertSame($excepted, $slugSource);
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new Category(), ['name', 'slug', 'description']);
    }
}
