<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Testimonial\Tests\Features\Http\Controllers\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;

class IndexTestimonialTest extends TestCase
{
    public function test_admin_cant_index_testimonials(): void
    {
        $this->actingAsAdmin();

        $response = $this->indexTestimonials();

        $response->assertStatus(200);
    }

    public function test_user_cant_index_testimonials(): void
    {
        $this->actingAsUser();

        $response = $this->indexTestimonials();

        $response->assertStatus(403);
    }

    public function test_guest_cant_index_testimonials(): void
    {
        $response = $this->indexTestimonials();

        $response->assertStatus(401);
    }

    private function indexTestimonials(): TestResponse
    {
        return $this->json('get', '/api/admin/testimonials');
    }
}
