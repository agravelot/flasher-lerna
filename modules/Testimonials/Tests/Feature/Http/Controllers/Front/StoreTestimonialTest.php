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

class StoreTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_testimonial(): void
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
        return $this->post('/api/admin/testimonials', [
            'name' => $testimonial->name,
            'description' => $testimonial->description,
        ]);
    }

    public function test_user_cannot_store_testimonial(): void
    {
        $this->actingAsUser();
        $testimonial = factory(GoldenBookPost::class)->make();

        $response = $this->storeTestimonial($testimonial);

        $response->assertStatus(403);
        $this->assertCount(0, GoldenBookPost::all());
    }

    public function test_guest_cannot_store_testimonial(): void
    {
        $testimonial = factory(GoldenBookPost::class)->make();

        $response = $this->storeTestimonial($testimonial);

        $response->assertStatus(401);
        $this->assertCount(0, GoldenBookPost::all());
    }
}
