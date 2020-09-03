<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\Testimonial;

use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_published_testimonials_and_ordered_with_latest(): void
    {
        $this->actingAsAdmin();
        $oldTestimonial = factory(Testimonial::class)->create(['published_at' => Carbon::now()->subMonth()]);
        $latestTestimonial = factory(Testimonial::class)->create(['published_at' => Carbon::now()]);

        $response = $this->json('get', '/api/testimonials?sort=-published_at');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $latestTestimonial->id)
            ->assertJsonPath('data.1.id', $oldTestimonial->id);
    }

    public function test_admin_can_not_view_unpublished_testimonials(): void
    {
        $this->actingAsAdmin();
        $testimonial = factory(Testimonial::class)->state('unpublished')->create();

        $response = $this->json('get', '/api/testimonials');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_user_can_view_published_testimonials(): void
    {
        $this->actingAsUser();
        $testimonial = factory(Testimonial::class)->states(['published'])->create();

        $response = $this->json('get', '/api/testimonials');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_user_can_not_view_unpublished_testimonials(): void
    {
        $this->actingAsUser();
        $testimonial = factory(Testimonial::class)->state('unpublished')->create();

        $response = $this->json('get', '/api/testimonials');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_guest_can_view_published_testimonials(): void
    {
        $testimonial = factory(Testimonial::class)->states(['published'])->create();

        $response = $this->json('get', '/api/testimonials');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_guest_can_not_view_unpublished_testimonials(): void
    {
        $testimonial = factory(Testimonial::class)->state('unpublished')->create();

        $response = $this->json('get', '/api/testimonials');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }
}
