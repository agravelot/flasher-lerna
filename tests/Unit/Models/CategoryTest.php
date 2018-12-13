<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class CategoryTest extends ModelTestCase
{
    use WithFaker, DatabaseMigrations;

    public function testRouteKeyName()
    {
        $post = new Category();
        $slug = $this->faker->slug;
        $routeKey = $post->getRouteKeyName();
        $post->$$routeKey = $slug;

        $this->assertEquals($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName() {
        $post = new Category();
        $routeKey = $post->getRouteKeyName();

        $excepted = "slug";

        $this->assertEquals($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle() {
        $post = new Category();
        $slugSource = $post->sluggable()['slug']['source'];

        $excepted = "name";

        $this->assertEquals($excepted, $slugSource);
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new Category(), ['name', 'slug', 'description']);
    }

    public function test_slug_generated_is_valid()
    {
        $category = factory(Category::class)->create([
            'name' => 'This is a name',
        ]);

        $this->assertEquals('this-is-a-name', $category->slug);
    }
}
