<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ModelTestCase;

class CategoryTest extends ModelTestCase
{
    use WithFaker, RefreshDatabase;

    public function test_can_not_create_two_categories_with_the_same_name(): void
    {
        $count = 0;
        $categories = factory(Category::class, 2)->make(['name' => 'A name']);

        try {
            $categories->each(static function (Category $category) use (&$count): void {
                $category->save();
                $count++;
            });
        } catch (QueryException $e) {
            $this->assertSame(1, $count);

            return;
        }

        $this->fail('We cannot create two categories with the same name');
    }

    public function test_can_create_a_category_with_an_empty_description(): void
    {
        $category = factory(Category::class)->make(['description' => null]);

        $category->save();

        $this->assertCount(1, Category::all());
    }

    public function testRouteKeyName(): void
    {
        $category = new Category();
        $slug = $this->faker->slug;
        $routeKey = $category->getRouteKeyName();
        $category->$$routeKey = $slug;

        $this->assertSame($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName(): void
    {
        $category = new Category();
        $routeKey = $category->getRouteKeyName();

        $excepted = 'slug';

        $this->assertSame($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle(): void
    {
        $category = new Category();
        $slugSource = $category->sluggable()['slug']['source'];

        $excepted = 'name';

        $this->assertSame($excepted, $slugSource);
    }

    public function testModelConfiguration(): void
    {
        $this->runConfigurationAssertions(new Category(), ['name', 'slug', 'meta_description', 'description']);
    }
}
