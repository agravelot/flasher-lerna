<?php

namespace Tests\Feature\Http\Controller\Admin\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_category(): void
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->make();
        $category->name = 'Test title';

        $response = $this->storeCategory($category);

        $response->assertCreated()
            ->assertSee($category->title);
        $this->assertCount(1, Category::all());
    }

    public function test_user_cannot_store_category(): void
    {
        $this->actingAsUser();
        $category = factory(Category::class)->make();

        $response = $this->storeCategory($category);

        $response->assertStatus(403);
        $this->assertCount(0, Category::all());
    }

    public function test_guest_cannot_store_category(): void
    {
        $category = factory(Category::class)->make();

        $response = $this->storeCategory($category);

        $response->assertStatus(401);
        $this->assertCount(0, Category::all());
    }

    private function storeCategory(Category $category): TestResponse
    {
        return $this->json('post', '/api/admin/categories', [
            'name' => $category->name,
            'meta_description' => $category->meta_description,
            'description' => $category->description,
        ]);
    }
}
