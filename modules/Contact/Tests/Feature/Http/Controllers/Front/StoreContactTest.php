<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Front\Contact;

use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Models\Contact;
use App\Models\PublishedTestimonial;
use App\Models\Testimonial;
use Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_store_a_contact(): void
    {
        $contact = factory(Contact::class)->make();
        $this->assertCount(0, Contact::all());

        $response = $this->storeContact($contact);

        $this->followRedirects($response)->assertOk();
        $this->assertCount(1, Contact::all());
    }

    private function storeContact(Contact $contact): TestResponse
    {
        // prevent validation error on captcha
        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);
        // provide hidden input for your 'required' validation
        NoCaptcha::shouldReceive('display')
            ->zeroOrMoreTimes()
            ->andReturn('<input type="hidden" name="g-recaptcha-response" value="1" />');
        NoCaptcha::shouldReceive('renderJs')
            ->zeroOrMoreTimes()
            ->andReturn('?');
        session()->setPreviousUrl('/contact');
        return $this->post('/contact', [
            'email' => $contact->email,
            'name' => $contact->name,
            'message' => $contact->message,
            'g-recaptcha-response' => '1',
        ]);
    }
}
