<?php

namespace Modules\Contact\Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_index_contacts(): void
    {
        $this->actingAsAdmin();
        $contacts = factory(Contact::class, 8)->create();

        $response = $this->getContacts();

        $response->assertStatus(200);
        $this->assertJsonFragments($response, $contacts);
    }

    private function getContacts(): TestResponse
    {
        return $this->json('get', '/api/admin/contacts');
    }

    private function assertJsonFragments(TestResponse $response, Collection $contacts): void
    {
        $contacts->each(static function (Contact $contact) use ($response) {
            $response->assertJsonFragment([
                'name' => $contact->name,
                'email' => $contact->email,
                'message' => $contact->message,
                'created_at' => $contact->created_at,
                'updated_at' => $contact->updated_at,
            ]);
        });
    }

    public function test_user_cannot_index_contacts(): void
    {
        $this->actingAsUser();
        $contacts = factory(Contact::class, 8)->create();

        $response = $this->getContacts();

        $response->assertStatus(403);
    }

    public function testGuestCannotIndexContacts(): void
    {
        $contacts = factory(Contact::class, 8)->create();

        $response = $this->getContacts();

        $response->assertStatus(401);
    }
}
