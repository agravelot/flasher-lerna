<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Testimonial\Tests\Features\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\GoldenBookPost;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_testimonial(): void
    {
        $this->actingAsAdmin();
        $testimonial = factory(GoldenBookPost::class)->create();

        $response = $this->deleteTestimonial($testimonial);

        $response->assertStatus(204);
        $this->assertNull($testimonial->fresh());
    }

    private function deleteTestimonial(GoldenBookPost $testimonial): TestResponse
    {
        return $this->json('delete', "/api/admin/testimonials/{$testimonial->id}");
    }

    public function test_user_cannot_delete_testimonial(): void
    {
        $this->actingAsUser();
        $testimonial = factory(GoldenBookPost::class)->create();

        $response = $this->deleteTestimonial($testimonial);

        $response->assertStatus(403);
        $this->assertNotNull($testimonial->fresh());
    }

    public function test_guest_cannot_delete_testimonial(): void
    {
        $testimonial = factory(GoldenBookPost::class)->create();

        $response = $this->deleteTestimonial($testimonial);

        $response->assertStatus(401);
        $this->assertNotNull($testimonial->fresh());
    }

    public function test_user_cant_index_testimonials(): void
    {
        $this->actingAsUser();
        $testimonial = factory(GoldenBookPost::class)->create();

        $response = $this->deleteTestimonial($testimonial);

        $response->assertStatus(403);
    }

    public function test_guest_cant_index_testimonials(): void
    {
        $testimonial = factory(GoldenBookPost::class)->create();

        $response = $this->deleteTestimonial($testimonial);

        $response->assertStatus(401);
    }
}
