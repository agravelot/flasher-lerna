<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Front\Category;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class DestroyCategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_admin_can_destroy_a_category()
    {
        $this->actingAsAdmin();

        /* @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->deleteCategory($category->slug);

        $this->assertSame(0, Category::count());
        $response->assertStatus(302);
        $response->assertRedirect('/admin/categories');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertDontSee($category->name)
            ->assertDontSee($category->description);
    }

    private function deleteCategory(string $slug): TestResponse
    {
        return $this->delete('/admin/categories/' . $slug);
    }

    public function test_user_can_not_destroy_a_category()
    {
        $this->actingAsUser();

        /* @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->deleteCategory($category->slug);

        $this->assertSame(1, Category::count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_destroy_a_category_and_is_redirected_to_login()
    {
        /* @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->deleteCategory($category->slug);

        $this->assertSame(1, Category::count());
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
