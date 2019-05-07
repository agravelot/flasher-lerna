<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Category;

use Tests\TestCase;
use App\Models\Category;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_a_category()
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->make();

        $response = $this->storeCategory($category);

        $this->assertSame(1, Category::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/categories');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($category->name)
            ->assertSee('Category successfully added')
            ->assertDontSee($category->description);
    }

    private function storeCategory(Category $category): TestResponse
    {
        session()->setPreviousUrl('/admin/categories/create');

        return $this->post('/admin/categories', $category->toArray());
    }

    public function test_admin_can_not_create_two_categories_with_the_same_name_and_redirect_with_error()
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();
        $duplicateNameCategory = factory(Category::class)->make(['name' => $category->name]);

        $response = $this->storeCategory($duplicateNameCategory);

        $this->assertSame(1, Category::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/categories/create');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee('The name has already been taken.');
    }

    public function test_user_can_not_store_a_category()
    {
        $this->actingAsUser();
        $category = factory(Category::class)->make();

        $response = $this->storeCategory($category);

        $this->assertSame(0, Category::count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_store_a_category_and_is_redirected_to_login()
    {
        $category = factory(Category::class)->make();

        $response = $this->storeCategory($category);

        $this->assertSame(0, Category::count());
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
