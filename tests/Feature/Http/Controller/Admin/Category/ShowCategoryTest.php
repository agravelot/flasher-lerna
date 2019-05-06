<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Category;

use Tests\TestCase;
use App\Models\User;
use App\Models\Album;
use App\Models\Category;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_a_category()
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();

        $response = $this->showCategory($category);

        $response->assertStatus(200)
            ->assertSee($category->name);
    }

    private function showCategory(Category $category): TestResponse
    {
        return $this->get('/admin/categories/'.$category->slug);
    }

    public function test_admin_can_view_a_category_with_albums()
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();
        $albums = factory(Album::class, 2)
            ->states(['published', 'passwordLess'])
            ->create(['user_id' => factory(User::class)->create()->id]);
        $category->albums()->saveMany($albums);

        $response = $this->showCategory($category);

        $response->assertStatus(200)
            ->assertSee($category->name);
        $albums->each(function (Album $album) use ($response) {
            $response->assertSee($album->title);
        });
    }

    public function test_user_can_not_view_a_category()
    {
        $this->actingAsUser();
        $category = factory(Category::class)->create();

        $response = $this->showCategory($category);

        $response->assertForbidden();
    }

    public function test_guest_can_not_view_a_category_and_is_redirected_to_login()
    {
        $category = factory(Category::class)->create();

        $response = $this->showCategory($category);

        $response->assertRedirect('/login');
        $this->followRedirects($response)
            ->assertStatus(200);
    }
}
