<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Front\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class IndexCategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_admin_can_view_index_page_with_multiple_categories()
    {
        $this->actingAsAdmin();
        $categories = factory(Category::class, 5)->create();

        $response = $this->showCategoryIndex();

        $response->assertStatus(200)
            ->assertDontSee('Nothing to show');

        $categories->each(function (Category $category) use ($response) {
            $response->assertSee($category->name);
        });
    }

    private function showCategoryIndex(): TestResponse
    {
        return $this->get('/admin/categories');
    }

    public function test_admin_can_view_index_page_with_one_category()
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();

        $response = $this->showCategoryIndex();

        $response->assertStatus(200)
            ->assertSee($category->name)
            ->assertDontSee('Nothing to show');
    }

    public function test_admin_can_view_index_page_with_no_category()
    {
        $this->actingAsAdmin();

        $response = $this->showCategoryIndex();

        $response->assertStatus(200)
            ->assertSee('Nothing to show');
    }

    public function test_guest_can_not_view_index_page_for_a_category_and_is_redirected_to_login()
    {
        $response = $this->showCategoryIndex();

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_user_can_not_view_index_page_for_a_category()
    {
        $this->actingAsUser();

        $response = $this->showCategoryIndex();

        $response->assertStatus(403);
    }
}
