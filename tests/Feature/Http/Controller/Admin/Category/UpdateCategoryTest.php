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

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_a_category_with_the_same_data()
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();

        $response = $this->updateCategory($category);

        $this->assertSame(1, Category::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/categories');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($category->name)
            ->assertDontSee($category->description);
    }

    private function updateCategory(Category $category, ?string $slug = null): TestResponse
    {
        if ($slug === null) {
            $slug = $category->slug;
        }

        session()->setPreviousUrl('/admin/categories/' . $slug . '/edit');

        return $this->patch('/admin/categories/' . $slug, $category->toArray());
    }

    public function test_admin_can_not_update_a_category_with_another_category_name()
    {
        $this->actingAsAdmin();
        $categories = factory(Category::class, 2)->create();
        $categories->get(1)->name = $categories->get(0)->name;

        $response = $this->updateCategory($categories->get(1), $categories->get(1)->slug);

        $this->assertSame(2, Category::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/categories/' . $categories->get(1)->slug . '/edit');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($categories->get(1)->name)
            ->assertSee($categories->get(1)->description)
            ->assertSee('The name has already been taken.');
    }

    public function test_user_can_not_update_a_category()
    {
        $this->actingAsUser();
        $category = factory(Category::class)->create();

        $response = $this->updateCategory(self::CATEGORY_DATA, $category);

        $this->assertSame(1, Category::count());
        $this->assertSame($category->id, $category->fresh()->id);
        $this->assertSame($category->title, $category->fresh()->title);
        $this->assertSame($category->description, $category->fresh()->description);
        $response->assertStatus(403);
    }

    public function test_guest_can_not_update_a_category_and_is_redirected_to_login()
    {
        $category = factory(Category::class)->create();

        $response = $this->updateCategory(self::CATEGORY_DATA, $category);

        $this->assertSame(1, Category::count());
        $this->assertSame($category->id, $category->fresh()->id);
        $this->assertSame($category->title, $category->fresh()->title);
        $this->assertSame($category->description, $category->fresh()->description);
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
