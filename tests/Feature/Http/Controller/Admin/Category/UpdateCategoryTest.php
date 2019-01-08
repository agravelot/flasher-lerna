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

class UpdateCategoryTest extends TestCase
{
    use DatabaseMigrations;

    const CATEGORY_DATA = [
        'name' => 'A category name',
        'description' => 'A random description',
    ];

    public function test_admin_can_update_a_category()
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();

        $response = $this->updateCategory(self::CATEGORY_DATA, $category);

        $this->assertSame(1, Category::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/categories/' . $category->slug);
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee(self::CATEGORY_DATA['name'])
            ->assertSee(self::CATEGORY_DATA['description']);
    }

    private function updateCategory(array $data, Category $category): TestResponse
    {
        session()->setPreviousUrl('/admin/categories/' . $category->slug . '/edit');

        return $this->patch('/admin/categories/' . $category->slug, $data);
    }

    public function test_admin_can_update_a_categories_with_the_same_name()
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();

        $response = $this->updateCategory([
            'name' => $category->name,
            'description' => 'updated description',
        ], $category);

        $this->assertSame(1, Category::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/categories/' . $category->slug);
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($category->name)
            ->assertSee('updated description')
            ->assertSee('Category successfully updated')
            ->assertDontSee('The name has already been taken.');
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
