<?php

namespace Tests\Feature\Http\Controller\Front\Testimonial;

use Tests\TestCase;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_see_empty_testimonial_index()
    {
        $response = $this->showTestimonials();

        $response->assertOk()
            ->assertSee('Nothing to show');
    }

    private function showTestimonials(): TestResponse
    {
        return $this->get('/testimonials');
    }

    public function test_guest_can_see_active_testimonial_index()
    {
        $goldenBookPosts = factory(Testimonial::class, 2)->state('published')->create();

        $response = $this->showTestimonials();

        $response->assertOk()
            ->assertDontSee('Nothing to show')
            ->assertSee($goldenBookPosts->get(0)->name)
            ->assertSee($goldenBookPosts->get(0)->body)
            ->assertDontSee($goldenBookPosts->get(0)->email)
            ->assertSee($goldenBookPosts->get(1)->name)
            ->assertSee($goldenBookPosts->get(1)->body)
            ->assertDontSee($goldenBookPosts->get(1)->email);
    }

    public function test_guest_can_not_see_unactive_testimonial_index()
    {
        $goldenBookPosts = factory(Testimonial::class, 2)->state('unpublished')->create();

        $response = $this->showTestimonials();

        $response->assertOk()
            ->assertSee('Nothing to show')
            ->assertDontSee($goldenBookPosts->get(0)->name)
            ->assertDontSee($goldenBookPosts->get(0)->body)
            ->assertDontSee($goldenBookPosts->get(0)->email)
            ->assertDontSee($goldenBookPosts->get(1)->name)
            ->assertDontSee($goldenBookPosts->get(1)->body)
            ->assertDontSee($goldenBookPosts->get(1)->email);
    }
}
