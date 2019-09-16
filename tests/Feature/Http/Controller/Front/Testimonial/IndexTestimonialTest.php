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

        $response->assertStatus(200);
        $response->assertSee('Nothing to show');
    }

    private function showTestimonials(): TestResponse
    {
        return $this->get('/testimonials');
    }

    public function test_guest_can_see_active_testimonial_index()
    {
        $goldenBookPosts = factory(Testimonial::class, 2)->state('published')->create();

        $response = $this->showTestimonials();

        $response->assertStatus(200);
        $response->assertDontSee('Nothing to show');
        $response->assertSee($goldenBookPosts->get(0)->name);
        $response->assertSee($goldenBookPosts->get(0)->body);
        $response->assertDontSee($goldenBookPosts->get(0)->email);
        $response->assertSee($goldenBookPosts->get(1)->name);
        $response->assertSee($goldenBookPosts->get(1)->body);
        $response->assertDontSee($goldenBookPosts->get(1)->email);
    }

    public function test_guest_can_not_see_unactive_testimonial_index()
    {
        $goldenBookPosts = factory(Testimonial::class, 2)->state('unpublished')->create();

        $response = $this->showTestimonials();

        $response->assertStatus(200);
        $response->assertSee('Nothing to show');
        $response->assertDontSee($goldenBookPosts->get(0)->name);
        $response->assertDontSee($goldenBookPosts->get(0)->body);
        $response->assertDontSee($goldenBookPosts->get(0)->email);
        $response->assertDontSee($goldenBookPosts->get(1)->name);
        $response->assertDontSee($goldenBookPosts->get(1)->body);
        $response->assertDontSee($goldenBookPosts->get(1)->email);
    }
}
