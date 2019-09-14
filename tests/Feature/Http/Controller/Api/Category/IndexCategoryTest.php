<?php

namespace Tests\Feature\Http\Controller\Api\Category;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_index_categories()
    {
        $response = $this->indexCategories();

        $response->assertStatus(200);
    }

    private function indexCategories(): TestResponse
    {
        return $this->json('get', '/api/categories');
    }

    public function test_guest_can_index_categories_with_data()
    {
        $categories = factory(Category::class, 5)->create();

        $response = $this->indexCategories();

        $response->assertStatus(200)
            ->assertSeeInOrder($categories->pluck('title')->toArray());
    }
}
