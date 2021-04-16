<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Admin\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_category(): void
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();

        $response = $this->deleteCategory($category);

        $response->assertStatus(204);
        $this->assertNull($category->fresh());
    }

    private function deleteCategory(Category $category): TestResponse
    {
        return $this->json('delete', "/api/admin/categories/{$category->slug}");
    }

    public function test_user_cannot_delete_category(): void
    {
        $this->actingAsUser();
        $category = factory(Category::class)->create();

        $response = $this->deleteCategory($category);

        $response->assertStatus(403);
        $this->assertNotNull($category->fresh());
    }

    public function test_guest_cannot_delete_category(): void
    {
        $category = factory(Category::class)->create();

        $response = $this->deleteCategory($category);

        $response->assertStatus(401);
        $this->assertNotNull($category->fresh());
    }
}
