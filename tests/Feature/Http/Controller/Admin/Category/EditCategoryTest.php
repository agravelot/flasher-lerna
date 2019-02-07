<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class EditCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_not_view_edit_page_for_a_category_and_is_redirected_to_login()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->showCategoryEdit($category->slug);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $response->assertDontSee($category->name);
        $response->assertDontSee($category->description);
    }

    private function showCategoryEdit(string $slug): TestResponse
    {
        return $this->get('/admin/categories/' . $slug . '/edit');
    }

    public function test_user_can_not_view_edit_page_for_a_category()
    {
        $this->actingAsUser();
        /** @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->showCategoryEdit($category->slug);

        $response->assertStatus(403);
        $response->assertDontSee($category->name);
        $response->assertDontSee($category->description);
    }

    public function test_admin_can_view_edit_page_for_a_category()
    {
        $this->actingAsAdmin();
        /** @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->showCategoryEdit($category->slug);

        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertSee($category->description);
    }

    public function test_admin_can_not_edit_non_existent_category()
    {
        $this->actingAsAdmin();

        $response = $this->showCategoryEdit('random-slug');

        $response->assertStatus(404);
    }
}
