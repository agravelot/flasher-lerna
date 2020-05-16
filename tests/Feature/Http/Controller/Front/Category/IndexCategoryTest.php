<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Front\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class IndexCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_view_nothing_to_show(): void
    {
        $response = $this->showCategories();

        $response->assertOk();
        $response->assertSee('Nothing to show');
    }

    private function showCategories(): TestResponse
    {
        return $this->get('categories');
    }

    public function test_guest_can_view_categories(): void
    {
        $categories = factory(Category::class, 2)->create();

        $response = $this->showCategories();

        $response->assertOk();
        $response->assertSee($categories->get(0)->title);
        $response->assertSee($categories->get(1)->title);
    }
}
