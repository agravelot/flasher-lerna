<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Testimonial\Tests\Features\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cant_index_testimonials(): void
    {
        $this->actingAsAdmin();
        $testimonial = factory(Testimonial::class)->create();

        $response = $this->getTestimonial($testimonial);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $testimonial->id,
                    'name' => $testimonial->name,
                    'email' => $testimonial->email,
                    'body' => $testimonial->body,
                    'published_at' => $testimonial->published_at->jsonSerialize(),
                    'created_at' => $testimonial->created_at->jsonSerialize(),
                    'updated_at' => $testimonial->updated_at->jsonSerialize(),
                ],
            ]);
    }

    private function getTestimonial(Testimonial $testimonial): TestResponse
    {
        return $this->json('get', "/api/admin/testimonials/{$testimonial->id}");
    }

    public function test_user_cant_index_testimonials(): void
    {
        $this->actingAsUser();
        $testimonial = factory(Testimonial::class)->create();

        $response = $this->getTestimonial($testimonial);

        $response->assertStatus(403);
    }

    public function test_guest_cant_index_testimonials(): void
    {
        $testimonial = factory(Testimonial::class)->create();

        $response = $this->getTestimonial($testimonial);

        $response->assertStatus(401);
    }
}
