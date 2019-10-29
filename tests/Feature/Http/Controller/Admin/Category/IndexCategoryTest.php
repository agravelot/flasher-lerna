<?php

namespace Tests\Features\Http\Controller\Admin\Category;

use Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;

class IndexCategoryTest extends TestCase
{
    public function test_admin_cant_index_categories(): void
    {
        $this->actingAsAdmin();

        $response = $this->indexCategories();

        $response->assertOk();
    }

    public function test_user_cant_index_categories(): void
    {
        $this->actingAsUser();

        $response = $this->indexCategories();

        $response->assertStatus(403);
    }

    public function test_guest_cant_index_categories(): void
    {
        $response = $this->indexCategories();

        $response->assertStatus(401);
    }

    private function indexCategories(): TestResponse
    {
        return $this->json('get', '/api/admin/categories');
    }
}
