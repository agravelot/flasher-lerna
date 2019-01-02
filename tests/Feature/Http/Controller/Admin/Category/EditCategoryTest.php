<?php

namespace Tests\Feature\Http\Controller\Front\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class EditCategoryTest extends TestCase
{

    use DatabaseMigrations;

    public function test_admin_can_edit_a_category()
    {
        $user = factory(User::class)->state('admin')->create();
        Auth::setUser($user);

        /** @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->showCategoryEdit($category);

        $response->assertStatus(200);
        $response->assertSee($category->title);
        $response->assertSee($category->description);
    }

    private function showCategoryEdit(Category $category): TestResponse
    {
        return $this->get('/admin/categories/' . $category->slug . '/edit');
    }

}