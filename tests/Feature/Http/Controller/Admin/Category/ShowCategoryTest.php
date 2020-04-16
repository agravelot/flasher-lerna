<?php

namespace Tests\Feature\Http\Controller\Admin\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ShowCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cant_index_categories(): void
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->state('withCover')->create();
        $category->setCover(UploadedFile::fake()->image('fake.jpg'));

        $response = $this->getCategory($category);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'links' => [
                        'related' => url("/categories/{$category->slug}"),
                    ],
                    'cover' => [
                        'id' => $category->cover->id,
                        'name' => $category->cover->name,
                        'file_name' => $category->cover->file_name,
                    ],
                ],
            ]);
    }

    private function getCategory(Category $category): TestResponse
    {
        return $this->json('get', "/api/admin/categories/{$category->slug}");
    }

    public function test_user_cant_index_categories(): void
    {
        $this->actingAsUser();
        $category = factory(Category::class)->create();

        $response = $this->getCategory($category);

        $response->assertStatus(403);
    }

    public function test_guest_cant_index_categories(): void
    {
        $category = factory(Category::class)->create();

        $response = $this->getCategory($category);

        $response->assertStatus(401);
    }
}
