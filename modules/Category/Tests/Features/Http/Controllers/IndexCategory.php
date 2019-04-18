<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Category\Tests\Features\Http\Controllers;

use App\Models\Category;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class IndexCategory extends TestCase
{
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
