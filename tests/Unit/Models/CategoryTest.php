<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Unit\Models;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class CategoryTest extends ModelTestCase
{
    use WithFaker, RefreshDatabase;

    public function test_can_not_create_two_categories_with_the_same_name()
    {
        $categories = factory(Category::class, 2)->make(['name' => 'A name']);

        try {
            $categories->each(function (Category $category) {
                $category->save();
            });
        } catch (QueryException $e) {
            $this->assertSame(1, Category::count());

            return;
        }

        $this->fail('We cannot create two categories with the same name');
    }

    public function test_can_create_a_category_with_an_empty_description()
    {
        $category = factory(Category::class)->make(['description' => null]);

        $category->save();

        $this->assertSame(1, Category::count());
    }

    public function testRouteKeyName()
    {
        $category = new Category();
        $slug = $this->faker->slug;
        $routeKey = $category->getRouteKeyName();
        $category->$$routeKey = $slug;

        $this->assertSame($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName()
    {
        $category = new Category();
        $routeKey = $category->getRouteKeyName();

        $excepted = 'slug';

        $this->assertSame($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle()
    {
        $category = new Category();
        $slugSource = $category->sluggable()['slug']['source'];

        $excepted = 'name';

        $this->assertSame($excepted, $slugSource);
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new Category(), ['name', 'slug', 'description']);
    }
}
