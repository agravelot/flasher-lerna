<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Category;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class StoreCategoryTest extends TestCase
{
    use RefreshDatabase;

    const CATEGORY_DATA = [
        'name' => 'A category name',
        'description' => 'A random description',
    ];

    public function test_admin_can_store_a_category()
    {
        $this->actingAsAdmin();

        $response = $this->storeCategory(self::CATEGORY_DATA);

        $this->assertSame(1, Category::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/categories');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee(self::CATEGORY_DATA['name'])
            ->assertSee('Category successfully added')
            ->assertDontSee(self::CATEGORY_DATA['description']);
    }

    private function storeCategory(array $data): TestResponse
    {
        session()->setPreviousUrl('/admin/categories/create');

        return $this->post('/admin/categories', $data);
    }

    public function test_admin_can_not_create_two_categories_with_the_same_name_and_redirect_with_error()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->storeCategory(['name' => $category->name]);

        $this->assertSame(1, Category::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/categories/create');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee(' The name has already been taken.');
    }

    public function test_user_can_not_store_a_category()
    {
        $this->actingAsUser();

        $response = $this->storeCategory(self::CATEGORY_DATA);

        $this->assertSame(0, Category::count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_store_a_category_and_is_redirected_to_login()
    {
        $response = $this->storeCategory(self::CATEGORY_DATA);

        $this->assertSame(0, Category::count());
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
