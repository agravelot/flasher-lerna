<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\Invitation;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AcceptInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_invited_user_can_accept_invitation_and_cosplayer_is_linked_to_user(): void
    {
        Mail::fake();
        $user = factory(User::class)->make();
        $this->actingAs($user, 'api');
        $invitation = factory(Invitation::class)->state('unconfirmed')->create([
            'created_at' => now()->subDays(5),
        ]);

        $response = $this->acceptInvitation($invitation);

        $response->assertOk();
        $this->assertNotNull($invitation->fresh()->confirmed_at);
        $this->assertSame($user->id, ($invitation->cosplayer->sso_id));
    }

    public function test_return_404_on_non_valid_uuid(): void
    {
        Mail::fake();
        $user = factory(User::class)->make();
        $this->actingAs($user, 'api');
        $invitation = factory(Invitation::class)->state('unconfirmed')->create([
            'created_at' => now()->subDays(5),
        ]);
        $invitation->id = 'bad-uuid';

        $response = $this->acceptInvitation($invitation);

        $response->assertNotFound();
    }

    public function test_user_can_not_accept_invitation_twice(): void
    {
        Mail::fake();
        $user = factory(User::class)->make();
        $this->actingAs($user, 'api');
        $invitation = factory(Invitation::class)->state('confirmed')->create([
            'created_at' => now()->subDay(),
        ]);

        $response = $this->acceptInvitation($invitation);

        $this->assertSame(401, $response->status());
        $this->assertSame($invitation->confirmed_at->toString(), $invitation->fresh()->confirmed_at->toString());
    }

    public function test_admin_can_not_accept_invitation_twice(): void
    {
        Mail::fake();
        $user = factory(User::class)->state('admin')->make();
        $this->actingAs($user, 'api');
        $invitation = factory(Invitation::class)->state('confirmed')->create([
            'created_at' => now()->subDay(),
        ]);

        $response = $this->acceptInvitation($invitation);

        $this->assertSame(401, $response->status());
        $this->assertSame($invitation->confirmed_at->toString(), $invitation->fresh()->confirmed_at->toString());
    }

    public function test_invited_user_can_not_accept_an_expired_invitation(): void
    {
        Mail::fake();
        $user = factory(User::class)->make();
        $this->actingAs($user, 'api');
        $invitation = factory(Invitation::class)->state('unconfirmed')->create([
            'created_at' => now()->subDays(20),
        ]);

        $response = $this->acceptInvitation($invitation);

        $this->assertSame(403, $response->status());
        $this->assertNull($invitation->fresh()->confirmed_at);
        $this->assertSame($user->sso_id, ($invitation->cosplayer->sso_id));
    }

    public function test_unauthenticated_can_not_accept_invitation_and_is_redirected_to_login(): void
    {
        Mail::fake();
        $invitation = factory(Invitation::class)->state('unconfirmed')->create([
            'created_at' => now()->subDay(),
        ]);

        $response = $this->acceptInvitation($invitation);

        $response->assertStatus(401);
    }

    private function acceptInvitation(Invitation $invitation): TestResponse
    {
        return $this->getJson("/api/invitations/{$invitation->id}/accept");
    }
}
