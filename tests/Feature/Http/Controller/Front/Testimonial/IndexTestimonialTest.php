<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Front\Testimonial;

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class IndexTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_see_empty_testimonial_index(): void
    {
        $response = $this->showTestimonials();

        $response->assertOk()
            ->assertSee('Nothing to show');
    }

    private function showTestimonials(): TestResponse
    {
        return $this->get('/testimonials');
    }

    public function test_guest_can_see_active_testimonial_index(): void
    {
        $testimonialPosts = factory(Testimonial::class, 2)->state('published')->create();

        $response = $this->showTestimonials();

        $response->assertOk()
            ->assertDontSee('Nothing to show')
            ->assertSee($testimonialPosts->get(0)->name)
            ->assertSee($testimonialPosts->get(0)->body)
            ->assertDontSee($testimonialPosts->get(0)->email)
            ->assertSee($testimonialPosts->get(1)->name)
            ->assertSee($testimonialPosts->get(1)->body)
            ->assertDontSee($testimonialPosts->get(1)->email);
    }

    public function test_guest_can_not_see_unactive_testimonial_index(): void
    {
        $testimonialPosts = factory(Testimonial::class, 2)->state('unpublished')->create();

        $response = $this->showTestimonials();

        $response->assertOk()
            ->assertSee('Nothing to show')
            ->assertDontSee($testimonialPosts->get(0)->name)
            ->assertDontSee($testimonialPosts->get(0)->body)
            ->assertDontSee($testimonialPosts->get(0)->email)
            ->assertDontSee($testimonialPosts->get(1)->name)
            ->assertDontSee($testimonialPosts->get(1)->body)
            ->assertDontSee($testimonialPosts->get(1)->email);
    }
}
