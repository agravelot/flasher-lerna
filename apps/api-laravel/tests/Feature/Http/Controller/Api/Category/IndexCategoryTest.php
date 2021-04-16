<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class IndexCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_index_categories(): void
    {
        $response = $this->indexCategories();

        $response->assertOk();
    }

    private function indexCategories(): TestResponse
    {
        return $this->json('get', '/api/categories');
    }

    public function test_guest_can_index_categories_with_data(): void
    {
        $categories = factory(Category::class, 5)->create();

        $response = $this->indexCategories();

        $response->assertOk()
            ->assertSeeInOrder($categories->pluck('title')->toArray());
    }
}
