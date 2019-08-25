<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Testimonial\Tests\Features\Http\Controllers\Front;

use Tests\TestCase;
use App\Models\Testimonial;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_a_testimonial(): void
    {
        $this->actingAsAdmin();
        /** @var Testimonial $testimonial */
        $testimonial = factory(Testimonial::class)->make();
        $testimonial->name = 'Test title';

        $response = $this->storeTestimonial($testimonial);

        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertDontSee($testimonial->name);
        $this->assertCount(1, Testimonial::all());
    }

    private function storeTestimonial(Testimonial $testimonial): TestResponse
    {
        // prevent validation error on captcha
        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);

        // provide hidden input for your 'required' validation
        NoCaptcha::shouldReceive('display')
            ->zeroOrMoreTimes()
            ->andReturn('<input type="hidden" name="g-recaptcha-response" value="1" />');
        session()->setPreviousUrl('/testimonials/create');

        return $this->post('/testimonials', [
            'name' => $testimonial->name,
            'email' => $testimonial->email,
            'body' => $testimonial->body,
            'g-recaptcha-response' => '1',
        ]);
    }

    public function test_user_can_store_a_testimonial(): void
    {
        $this->actingAsUser();
        /** @var Testimonial $testimonial */
        $testimonial = factory(Testimonial::class)->make();
        $testimonial->name = 'Test title';

        $response = $this->storeTestimonial($testimonial);

        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertDontSee($testimonial->name);
        $this->assertCount(1, Testimonial::all());
    }

    public function test_guest_can_store_a_testimonial(): void
    {
        /** @var Testimonial $testimonial */
        $testimonial = factory(Testimonial::class)->make();
        $testimonial->name = 'Test title';

        $response = $this->storeTestimonial($testimonial);

        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertDontSee($testimonial->name);
        $this->assertCount(1, Testimonial::all());
    }
}
