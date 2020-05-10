<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ShowCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_show_category_with_data(): void
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
