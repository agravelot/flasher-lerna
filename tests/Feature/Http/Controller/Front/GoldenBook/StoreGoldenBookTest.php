<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Front\GoldenBook;

use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Models\GoldenBookPost;
use App\Models\PublishedGoldenBookPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class StoreGoldenBookTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_post_to_the_golden_book()
    {
        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);
        $data = ['g-recaptcha-response' => '1'];
        $post = factory(GoldenBookPost::class)->make();

        $response = $this->storeGoldenbookPost($post, $data);

        $response->assertRedirect('/goldenbook');
        $this->assertSame(1, GoldenBookPost::count());
        $this->assertSame(0, PublishedGoldenBookPost::count());
        $this->followRedirects($response)
            ->assertSee('Your message has been added to the golden book');
    }

    private function storeGoldenbookPost(GoldenBookPost $goldenBookPost, array $data = []): TestResponse
    {
        session()->setPreviousUrl('/goldenbook');

        return $this->post('/goldenbook', array_merge($goldenBookPost->toArray(), $data));
    }

    public function test_guest_can_not_post_to_the_golden_book_without_captcha()
    {
        $post = factory(GoldenBookPost::class)->make();

        $response = $this->storeGoldenbookPost($post);

        $response->assertRedirect('/goldenbook');
        $this->assertSame(0, GoldenBookPost::count());
        $this->assertSame(0, PublishedGoldenBookPost::count());
        $this->followRedirects($response)
            ->assertSee(' The g-recaptcha-response field is required.')
            ->assertDontSee('Your message has been added to the golden book');
    }

    public function test_admin_can_post_to_the_golden_book()
    {
        $this->actingAsAdmin();
        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);
        $data = ['g-recaptcha-response' => '1'];
        $post = factory(GoldenBookPost::class)->make();

        $response = $this->storeGoldenbookPost($post, $data);

        $response->assertRedirect('/goldenbook');
        $this->assertSame(1, GoldenBookPost::count());
        $this->assertSame(0, PublishedGoldenBookPost::count());
        $this->followRedirects($response)
            ->assertSee('Your message has been added to the golden book')
            ->assertDontSee($post->name);
    }

    public function test_admin_can_not_post_to_the_golden_book_without_captcha()
    {
        $this->actingAsAdmin();
        $post = factory(GoldenBookPost::class)->make();

        $response = $this->storeGoldenbookPost($post);

        $response->assertRedirect('/goldenbook');
        $this->assertSame(0, GoldenBookPost::count());
        $this->assertSame(0, PublishedGoldenBookPost::count());
        $this->followRedirects($response)
            ->assertSee(' The g-recaptcha-response field is required.')
            ->assertDontSee('Your message has been added to the golden book');
    }
}
