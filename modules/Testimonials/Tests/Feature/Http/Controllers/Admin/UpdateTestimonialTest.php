<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Testimonail\Tests\Features\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\GoldenBookPost;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_publish_an_unpublished_testimonial()
    {
        $this->disableExceptionHandling();
        $this->actingAsAdmin();
        $testimonial = factory(GoldenBookPost::class)->state('unpublished')->create();
        $this->assertFalse($testimonial->isPublished(), 'Testimonial should be published');

        $testimonial->publish();
        $response = $this->updateTestimonial($testimonial);
        $response->assertStatus(200);
        $this->assertTrue($testimonial->fresh()->isPublished(), 'Testimonial should not be published');
    }

    private function updateTestimonial(GoldenBookPost $testimonial): TestResponse
    {
        return $this->json('put', "/api/admin/testimonials/{$testimonial->id}", [
            'published_at' => $testimonial->published_at,
        ]);
    }

    public function test_admin_can_unpublish_an_published_testimonial()
    {
        $this->actingAsAdmin();
        $testimonial = factory(GoldenBookPost::class)->state('published')->create();
        $this->assertTrue($testimonial->isPublished(), 'Testimonial should not be published');

        $testimonial->unpublish();
        $response = $this->updateTestimonial($testimonial);
        $response->assertStatus(200);
        $this->assertFalse($testimonial->fresh()->isPublished(), 'Testimonial should be published');
    }
}
