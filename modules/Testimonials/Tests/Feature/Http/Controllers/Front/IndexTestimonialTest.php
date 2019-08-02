<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Testimonial\Tests\Feature\Http\Controllers\Front;

use Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_index_testimonials()
    {
        $response = $this->indexCategories();

        $response->assertStatus(200);
    }

    private function indexCategories(): TestResponse
    {
        return $this->json('get', '/api/testimonials');
    }

    public function test_guest_can_index_testimonials_with_data()
    {
        $testimonials = factory(GoldenBookPost::class, 5)->create();

        $response = $this->indexCategories();

        $response->assertStatus(200)
            ->assertSeeInOrder($testimonials->pluck('title')->toArray());
    }
}
