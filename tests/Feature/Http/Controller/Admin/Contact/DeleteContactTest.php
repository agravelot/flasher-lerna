<?php

namespace Modules\Contact\Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\Contact;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_a_contact(): void
    {
        $this->actingAsAdmin();
        $contact = factory(Contact::class)->create();

        $response = $this->deleteContact($contact);

        $response->assertStatus(204);
        $this->assertNull($contact->fresh());
    }

    public function deleteContact(Contact $contact): TestResponse
    {
        return $this->json('delete', "/api/admin/contacts/{$contact->id}");
    }

    public function test_user_cannot_delete_a_contact(): void
    {
        $this->actingAsUser();
        $contact = factory(Contact::class)->create();

        $response = $this->deleteContact($contact);

        $response->assertStatus(403);
        $this->assertNotNull($contact->fresh());
    }

    public function test_guest_cannot_delete_a_contact(): void
    {
        $contact = factory(Contact::class)->create();

        $response = $this->deleteContact($contact);

        $response->assertStatus(401);
        $this->assertNotNull($contact->fresh());
    }
}
