<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Category\Tests\Feature\Http\Controllers\Front;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_index_categories_with_data()
    {
        $category = factory(Category::class)->create();

        $response = $this->showCategory($category);

        $response->assertStatus(200)
            ->assertSee($category->title);
    }

    private function showCategory(Category $category): TestResponse
    {
        return $this->json('get', "/api/categories/{$category->slug}");
    }
}
