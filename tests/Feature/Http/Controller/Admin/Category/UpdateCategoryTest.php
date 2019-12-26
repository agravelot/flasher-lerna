<?php

namespace Tests\Features\Http\Controller\Admin\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_category_with_same_title(): void
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();
        $category->description = 'Desc test';

        $response = $this->updateCategory($category);

        $response->assertOk()
            ->assertSee($category->title);
        $this->assertSame($category->name, $category->fresh()->name);
    }

    private function updateCategory(Category $category): TestResponse
    {
        return $this->json('put', "/api/admin/categories/{$category->slug}", [
            'name' => $category->name,
            'description' => $category->description,
        ]);
    }

    public function test_admin_can_update_title_and_update_slug(): void
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();
        $category->name = $title = 'Title test';

        $response = $this->updateCategory($category);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                ],
            ]);
        $this->assertSame($title, $category->fresh()->name);
        $this->assertSame(Str::slug($title), $category->fresh()->slug);
    }
}
