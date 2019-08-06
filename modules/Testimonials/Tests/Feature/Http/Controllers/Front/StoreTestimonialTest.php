<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Testimonial\Tests\Features\Http\Controllers\Front;

use Tests\TestCase;
use App\Models\GoldenBookPost;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_a_testimonial(): void
    {
        $this->actingAsAdmin();
        $testimonial = factory(GoldenBookPost::class)->make();
        $testimonial->name = 'Test title';

        $response = $this->storeTestimonial($testimonial);

        $response->assertStatus(201)
            ->assertSee($testimonial->title);
        $this->assertCount(1, GoldenBookPost::all());
    }

    private function storeTestimonial(GoldenBookPost $testimonial): TestResponse
    {
        return $this->post('/testimonials', [
            'name' => $testimonial->name,
            'description' => $testimonial->description,
        ]);
    }

    public function test_user_can_store_a_testimonial(): void
    {
        $this->actingAsUser();
        $testimonial = factory(GoldenBookPost::class)->make();
        $testimonial->name = 'Test title';

        $response = $this->storeTestimonial($testimonial);

        $response->assertStatus(201)
            ->assertSee($testimonial->title);
        $this->assertCount(1, GoldenBookPost::all());
    }

    public function test_guest_can_store_a_testimonial(): void
    {
        $testimonial = factory(GoldenBookPost::class)->make();
        $testimonial->name = 'Test title';

        $response = $this->storeTestimonial($testimonial);

        $response->assertStatus(201)
            ->assertSee($testimonial->title);
        $this->assertCount(1, GoldenBookPost::all());
    }
}
