<?php

namespace Tests\Feature\Http\Controller\Front\Contact;

use Tests\TestCase;
use App\Models\Contact;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
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
