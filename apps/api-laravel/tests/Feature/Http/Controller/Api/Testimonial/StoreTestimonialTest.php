<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\Testimonial;

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_store_testimonial(): void
    {
        $this->assertCount(0, Testimonial::all());
        $testimonial = factory(Testimonial::class)->make();

        $response = $this->storeTestimonial($testimonial);

        $response->assertCreated();
        $this->assertCount(1, Testimonial::all());
    }

    private function storeTestimonial(Testimonial $testimonial): TestResponse
    {
        return $this->post('/api/testimonials', [
            'name' => $testimonial->name,
            'body' => $testimonial->body,
            'email' => $testimonial->email,
        ]);
    }
}
