<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\Contact;

use App\Adapters\Keycloak\GroupRepresentation;
use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use App\Mail\ContactSent;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_store_a_contact_and_admins_are_notified(): void
    {
        $this->withoutExceptionHandling();
        Mail::fake();
        $admin1 = UserRepresentation::factory(['groups' => ['admin']]);
        $admin2 = UserRepresentation::factory(['groups' => ['admin']]);
        $admin3 = UserRepresentation::factory();
        $mockGroup = new GroupRepresentation();
        $mockGroup->id = '';
        Keycloak::shouldReceive('groups->first')->andReturn($mockGroup);
        Keycloak::shouldReceive('groups->members')->once()->andReturn([$admin1->toUser(), $admin2->toUser()]);
        $contact = factory(Contact::class)->make();
        $this->assertCount(0, Contact::all());
        Mail::assertNotSent(ContactSent::class);

        $response = $this->storeContact($contact);

        $response->assertCreated();
        $this->assertCount(1, Contact::all());

        Mail::assertQueued(
            ContactSent::class,
            static function (ContactSent $mail) use ($admin1, $admin2, $admin3) {
                return $mail->hasTo($admin1) && $mail->hasTo($admin2) && ! $mail->hasTo($admin3);
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
