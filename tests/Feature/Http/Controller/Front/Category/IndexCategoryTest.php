<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Front\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class IndexCategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_view_nothing_to_show()
    {
        $response = $this->showCategories();

        $response->assertStatus(200);
        $response->assertSee('Nothing to show');
    }

    private function showCategories(): TestResponse
    {
        return $this->get('categories');
    }

    public function test_guest_can_view_categories()
    {
        $categories = factory(Category::class, 2)->create();

        $response = $this->showCategories();

        $response->assertStatus(200);
        $response->assertSee($categories->get(0)->title);
        $response->assertSee($categories->get(1)->title);
    }
}
