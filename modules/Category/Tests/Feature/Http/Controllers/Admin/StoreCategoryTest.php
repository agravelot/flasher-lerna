<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Category\Tests\Features\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_category()
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->make();
        $category->name = 'Test title';

        $response = $this->storeCategory($category);

        $response->assertStatus(201)
            ->assertSee($category->title);
        $this->assertCount(1, Category::all());
    }

    private function storeCategory(Category $category): TestResponse
    {
        return $this->json('post', "/api/admin/categories/{$category->slug}", [
            'name' => $category->name,
            'description' => $category->description,
        ]);
    }
}
