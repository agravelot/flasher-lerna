<?php

namespace Tests\Feature\Http\Controller\Api\Admin\Testimonials;

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdateTestimonialsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_publish_testimonial(): void
    {
        $this->actingAsAdmin();
        /** @var Testimonial $testimonial */
        $testimonial = factory(Testimonial::class)->state('unpublished')->create();
        $this->assertCount(0, Testimonial::published()->get());
        $publishedAt = Carbon::parse('2017-05-25');

        $testimonial->published_at = $publishedAt;
        $response = $this->updateTestimonial($testimonial);

        $response->assertOk()
            ->assertJsonPath('data.published_at', $publishedAt->toJSON());
        $this->assertCount(1, Testimonial::published()->get());
    }

    public function test_admin_can_unpublish_testimonial(): void
    {
        $this->actingAsAdmin();
        /** @var Testimonial $testimonial */
        $testimonial = factory(Testimonial::class)->state('published')->create();
        $this->assertCount(1, Testimonial::published()->get());

        $testimonial->published_at = null;
        $response = $this->updateTestimonial($testimonial);

        $response->assertOk()
            ->assertJsonPath('data.published_at', null);
        $this->assertCount(0, Testimonial::published()->get());
    }

    private function updateTestimonial(Testimonial $testimonial): TestResponse
    {
        return $this->json('PATCH', "/api/admin/testimonials/{$testimonial->id}", [
            'published_at' => $testimonial->published_at,
        ]);
    }
}
