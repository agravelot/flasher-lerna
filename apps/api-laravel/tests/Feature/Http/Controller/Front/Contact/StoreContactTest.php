<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Front\Contact;

use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Adapters\Keycloak\GroupRepresentation;
use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use App\Models\Contact;
use App\Notifications\ContactSent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_store_a_contact_and_admins_are_notified(): void
    {
        $this->withoutExceptionHandling();
        Notification::fake();
        $admin1 = UserRepresentation::factory(['groups' => ['admin']]);
        $admin2 = UserRepresentation::factory(['groups' => ['admin']]);
        $mockGroup = new GroupRepresentation();
        $mockGroup->id = '';
        Keycloak::shouldReceive('groups->first')->andReturn($mockGroup);
        Keycloak::shouldReceive('groups->members')->once()->andReturn([$admin1->toUser(), $admin2->toUser()]);
        $contact = factory(Contact::class)->make();
        $this->assertCount(0, Contact::all());
        Notification::assertNotSentTo([$admin1->toUser(), $admin2->toUser()], ContactSent::class);

        $response = $this->storeContact($contact);

        $this->followRedirects($response)->assertOk();
        $this->assertCount(1, Contact::all());

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            ContactSent::class,
            static function ($notification, $channels, $notifiable) use ($admin1, $admin2) {
                return $notifiable->routes['mail'] === [$admin1->email, $admin2->email];
            }
        );
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
