<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Testimonial\Tests\Features\Http\Controllers\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_index_published_and_unpublished_testimonials(): void
    {
        $this->actingAsAdmin();
        $publishedTestimonial = factory(\App\Models\Testimonial::class)
            ->state('published')
            ->create();
        $unpublishedTestimonial = factory(\App\Models\Testimonial::class)
            ->state('unpublished')
            ->create();

        $response = $this->indexTestimonials();

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $publishedTestimonial->id,
                        'name' => $publishedTestimonial->name,
                        'email' => $publishedTestimonial->email,
                        'body' => $publishedTestimonial->body,
                        'published_at' => $publishedTestimonial->published_at->jsonSerialize(),
                        'created_at' => $publishedTestimonial->created_at->jsonSerialize(),
                        'updated_at' => $publishedTestimonial->updated_at->jsonSerialize(),
                    ],
                    [
                        'id' => $unpublishedTestimonial->id,
                        'name' => $unpublishedTestimonial->name,
                        'email' => $unpublishedTestimonial->email,
                        'body' => $unpublishedTestimonial->body,
                        'published_at' => null,
                        'created_at' => $unpublishedTestimonial->created_at->jsonSerialize(),
                        'updated_at' => $unpublishedTestimonial->updated_at->jsonSerialize(),
                    ],
                ],
            ]);
    }

    private function indexTestimonials(): TestResponse
    {
        return $this->json('get', '/api/admin/testimonials');
    }

    public function test_user_cant_index_testimonials(): void
    {
        $this->actingAsUser();

        $response = $this->indexTestimonials();

        $response->assertStatus(403);
    }

    public function test_guest_cant_index_testimonials(): void
    {
        $response = $this->indexTestimonials();

        $response->assertStatus(401);
    }
}
