<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Front\Testimonial;

use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Models\PublishedTestimonial;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_post_to_the_testimonial_and_is_not_published(): void
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
            ->assertSee('Your message has been added to the testimonials');
    }

    private function storeTestimonial(Testimonial $testimonialPost, array $data = []): TestResponse
    {
        session()->setPreviousUrl('/testimonials');

        return $this->post('/testimonials', array_merge($testimonialPost->toArray(), $data));
    }

    public function test_guest_can_not_post_to_the_testimonial_without_captcha(): void
    {
        $post = factory(Testimonial::class)->make();

        $response = $this->storeTestimonial($post);

        $response->assertRedirect('/testimonials');
        $this->assertSame(0, Testimonial::count());
        $this->assertSame(0, PublishedTestimonial::count());
        $this->followRedirects($response)
            ->assertSee(' The g-recaptcha-response field is required.')
            ->assertDontSee('Your message has been added to the testimonial');
    }

    public function test_admin_can_post_to_the_testimonial(): void
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
            ->assertSee('Your message has been added to the testimonials')
            ->assertDontSee($post->name);
    }

    public function test_admin_can_not_post_to_the_testimonial_without_captcha(): void
    {
        $this->actingAsAdmin();
        $post = factory(Testimonial::class)->make();

        $response = $this->storeTestimonial($post);

        $response->assertRedirect('/testimonials');
        $this->assertSame(0, Testimonial::count());
        $this->assertSame(0, PublishedTestimonial::count());
        $this->followRedirects($response)
            ->assertSee(' The g-recaptcha-response field is required.')
            ->assertDontSee('Your message has been added to the testimonial');
    }
}
