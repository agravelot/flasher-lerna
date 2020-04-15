<?php

namespace Tests\Feature\Http\Controller\Api\Admin\Invitation;

use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ShowInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_show_invitations(): void
    {
        Mail::fake();
        /** @var Collection $invitations */
        $invitation = factory(Invitation::class)->create();
        $this->actingAsAdmin();

        $response = $this->showInvitation($invitation);

        $response->assertOk();
        $this->assertInvitationsJsonFragment($response, $invitation);
    }

    public function test_user_cannot_show_invitations(): void
    {
        Mail::fake();
        $this->actingAsUser();
        $invitation = factory(Invitation::class)->create();

        $response = $this->showInvitation($invitation);

        $this->assertSame(403, $response->status());
    }

    public function test_guest_cannot_show_invitations(): void
    {
        Mail::fake();
        $invitation = factory(Invitation::class)->create();

        $response = $this->showInvitation($invitation);

        $this->assertSame(401, $response->status());
    }

    public function showInvitation(Invitation $invitation): TestResponse
    {
        return $this->json('GET', "/api/admin/invitations/{$invitation->id}");
    }

    private function assertInvitationsJsonFragment(TestResponse $response, Invitation $invitation): void
    {
        $response->assertJsonFragment([
            'id' => $invitation->id,
            'email' => $invitation->email,
            'message' => $invitation->message,
            // 'cosplayer', // ignore content here
        ]);
    }
}
