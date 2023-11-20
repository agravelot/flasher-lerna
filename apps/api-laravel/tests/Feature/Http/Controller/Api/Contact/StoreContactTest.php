<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\Contact;

use App\Adapters\Keycloak\GroupRepresentation;
use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use App\Models\Contact;
use App\Mail\ContactSent;
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

        $response->assertCreated();
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
        return $this->post('/api/contact', [
            'email' => $contact->email,
            'name' => $contact->name,
            'message' => $contact->message,
        ]);
    }
}
