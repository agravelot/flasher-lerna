<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Features\Http\Controller\Admin\Category;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_category(): void
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->make();
        $category->name = 'Test title';

        $response = $this->storeCategory($category);

        $response->assertStatus(201)
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
            'description' => $category->description,
        ]);
    }
}
