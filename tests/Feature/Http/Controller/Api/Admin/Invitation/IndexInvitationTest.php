<?php

namespace Tests\Feature\Http\Controller\Api\Admin\Invitation;

use Tests\TestCase;
use App\Models\Invitation;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_index_invitations(): void
    {
        /** @var Collection $invitations */
        $invitations = factory(Invitation::class, 5)->create();
        $this->actingAsAdmin();

        $response = $this->indexInvitations();

        $response->assertSuccessful();
        $invitations->each(function ($invitation) use ($response): void {
            $this->assertInvitationsJsonFragment($response, $invitation);
        });
    }

    public function test_user_cannot_index_invitations(): void
    {
        $this->actingAsUser();

        $response = $this->indexInvitations();

        $this->assertSame(403, $response->status());
    }

    public function test_guest_cannot_index_invitations(): void
    {
        $response = $this->indexInvitations();

        $this->assertSame(401, $response->status());
    }

    public function indexInvitations(): TestResponse
    {
        return $this->json('GET', '/api/admin/invitations');
    }

    private function assertInvitationsJsonFragment(TestResponse $response, Invitation $invitation): void
    {
        $response->assertJsonFragment([
            'id' => $invitation->id,
            'email' => $invitation->email,
            'message' => $invitation->message,
            'cosplayer', // ignore content here
        ]);
    }
}
