<?php

namespace Tests\Feature\Http\Controller\Api\Category;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_show_category_with_data()
    {
        $category = factory(Category::class)->create();

        $response = $this->showCategory($category);

        $response->assertOk()
            ->assertSee($category->title);
    }

    private function showCategory(Category $category): TestResponse
    {
        return $this->json('get', "/api/categories/{$category->slug}");
    }
}
