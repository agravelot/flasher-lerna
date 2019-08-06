<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Front\Testimonial;

use Tests\TestCase;
use App\Models\Testimonial;
use App\Models\PublishedTestimonial;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_post_to_the_testimonial_and_is_not_published()
    {
        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);
        $data = ['g-recaptcha-response' => '1'];
        $post = factory(Testimonial::class)->make();

        $response = $this->storeTestimonial($post, $data);

        $response->assertRedirect('/testimonials');
        $this->assertSame(1, Testimonial::count());
        $this->assertSame(0, PublishedTestimonial::count());
        $this->followRedirects($response)
            ->assertSee('Your message has been added to the golden book');
    }

    private function storeTestimonial(Testimonial $goldenBookPost, array $data = []): TestResponse
    {
        session()->setPreviousUrl('/testimonials');

        return $this->post('/testimonials', array_merge($goldenBookPost->toArray(), $data));
    }

    public function test_guest_can_not_post_to_the_testimonial_without_captcha()
    {
        $post = factory(Testimonial::class)->make();

        $response = $this->storeTestimonial($post);

        $response->assertRedirect('/testimonials');
        $this->assertSame(0, Testimonial::count());
        $this->assertSame(0, PublishedTestimonial::count());
        $this->followRedirects($response)
            ->assertSee(' The g-recaptcha-response field is required.')
            ->assertDontSee('Your message has been added to the golden book');
    }

    public function test_admin_can_post_to_the_testimonial()
    {
        $this->actingAsAdmin();
        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);
        $data = ['g-recaptcha-response' => '1'];
        $post = factory(Testimonial::class)->make();

        $response = $this->storeTestimonial($post, $data);

        $response->assertRedirect('/testimonials');
        $this->assertSame(1, Testimonial::count());
        $this->assertSame(0, PublishedTestimonial::count());
        $this->followRedirects($response)
            ->assertSee('Your message has been added to the golden book')
            ->assertDontSee($post->name);
    }

    public function test_admin_can_not_post_to_the_testimonial_without_captcha()
    {
        $this->actingAsAdmin();
        $post = factory(Testimonial::class)->make();

        $response = $this->storeTestimonial($post);

        $response->assertRedirect('/testimonials');
        $this->assertSame(0, Testimonial::count());
        $this->assertSame(0, PublishedTestimonial::count());
        $this->followRedirects($response)
            ->assertSee(' The g-recaptcha-response field is required.')
            ->assertDontSee('Your message has been added to the golden book');
    }
}
