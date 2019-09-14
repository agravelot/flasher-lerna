<?php

namespace Tests\Features\Http\Controller\Admin\Category;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreCoverCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_cover_category(): void
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();

        $response = $this->updateCoverCategory($category);

        $response->assertStatus(201)
            ->assertJson(['name' => "{$category->slug}.png"]);
        $this->assertNotNull($category->cover);
    }

    private function updateCoverCategory(Category $category, ?string $imageName = 'test.png'): TestResponse
    {
        return $this->json('post', '/api/admin/cover-categories', [
            'category_slug' => $category->slug,
            'file' => UploadedFile::fake()->image($imageName),
        ]);
    }

    public function test_user_cannot_store_cover_category(): void
    {
        $this->actingAsUser();
        $category = factory(Category::class)->create();

        $response = $this->updateCoverCategory($category);

        $response->assertStatus(403);
        $this->assertNull($category->fresh()->cover);
    }

    public function test_guest_cannot_store_cover_category(): void
    {
        $category = factory(Category::class)->create();

        $response = $this->updateCoverCategory($category);

        $response->assertStatus(401);
        $this->assertNull($category->fresh()->cover);
    }
}
