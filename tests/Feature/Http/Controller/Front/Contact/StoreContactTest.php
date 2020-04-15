<?php

namespace Tests\Feature\Http\Controller\Front\Contact;

use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Models\Contact;
use App\Models\User;
use App\Notifications\ContactSent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StoreContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_store_a_contact_and_admins_are_notified(): void
    {
        Notification::fake();
        $admins = factory(User::class, 5)->state('admin')->create();
        $contact = factory(Contact::class)->make();
        $this->assertCount(0, Contact::all());
        Notification::assertNotSentTo($admins, ContactSent::class);

        $response = $this->storeContact($contact);

        $this->followRedirects($response)->assertOk();
        $this->assertCount(1, Contact::all());
        Notification::assertSentTo($admins, ContactSent::class);
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
            ->andReturn(
                '<input type="hidden" name="g-recaptcha-response" value="1" />'
            );
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
