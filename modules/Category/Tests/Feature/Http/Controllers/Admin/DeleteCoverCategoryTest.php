<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Category\Tests\Features\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteCoverCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_cover_category(): void
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();
        $category->setCover(UploadedFile::fake()->image('test.png'));
        $this->assertNotNull($category->fresh()->cover);

        $response = $this->deleteCoverCategory($category);

        $response->assertStatus(204)
            ->assertJson([
                'data' => [
                    'cover' => null,
                ],
            ]);
        $this->assertNull($category->fresh()->cover);
    }

    private function deleteCoverCategory(Category $category): TestResponse
    {
        return $this->json('delete', "/api/admin/cover-categories/{$category->slug}");
    }

    public function test_admin_delete_non_existent_cover_category(): void
    {
        $this->actingAsAdmin();
        $category = factory(Category::class)->create();
        $this->assertNull($category->fresh()->cover);

        $response = $this->deleteCoverCategory($category);

        $response->assertStatus(204);
        $this->assertNull($category->fresh()->cover);
    }

    public function test_user_cannot_delete_cover_category(): void
    {
        $this->actingAsUser();
        $category = factory(Category::class)->create();

        $response = $this->deleteCoverCategory($category);

        $response->assertStatus(403);
        $this->assertNull($category->fresh()->cover);
    }

    public function test_guest_cannot_delete_cover_category(): void
    {
        $category = factory(Category::class)->create();

        $response = $this->deleteCoverCategory($category);

        $response->assertStatus(401);
        $this->assertNull($category->fresh()->cover);
    }
}
