<?php

namespace Tests\Feature\Http\Controller\Front\DownloadAlbum;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AcceptInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_invited_user_can_accept_invitation_and_cosplayer_is_linked_to_user(): void
    {
        Mail::fake();
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $invitation = factory(Invitation::class)->state('unconfirmed')->create([
            'created_at' => now()->subDays(5),
        ]);

        $response = $this->acceptInvitation($invitation);

        $response->assertOk();
        $this->assertNotNull($invitation->fresh()->confirmed_at);
        $this->assertTrue($user->is($invitation->cosplayer->user));
    }

    public function test_invited_user_can_not_accept_invitation_twice(): void
    {
        Mail::fake();
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $invitation = factory(Invitation::class)->state('confirmed')->create([
            'created_at' => now()->subDay(),
        ]);

        $response = $this->acceptInvitation($invitation);

        $this->assertSame(403, $response->status());
        $this->assertSame($invitation->confirmed_at->toString(), $invitation->fresh()->confirmed_at->toString());
    }

    public function test_invited_user_can_not_accept_an_expired_invitation()
    {
        Mail::fake();
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $invitation = factory(Invitation::class)->state('unconfirmed')->create([
            'created_at' => now()->subDays(20),
        ]);

        $response = $this->acceptInvitation($invitation);

        $this->assertSame(403, $response->status());
        $this->assertNull($invitation->fresh()->confirmed_at);
        $this->assertFalse($user->is($invitation->cosplayer->user));
    }

    public function test_unauthenticated_can_not_accept_invitation_and_is_redirected_to_login(): void
    {
        Mail::fake();
        $invitation = factory(Invitation::class)->state('unconfirmed')->create([
            'created_at' => now()->subDay(),
        ]);

        $response = $this->acceptInvitation($invitation);

        $response->assertRedirect('/login');
        $this->followRedirects($response)
            ->assertOk();
    }

    public function test_accepting_invitation_without_valid_signature_return_403_and_is_not_linked(): void
    {
        Mail::fake();
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $invitation = factory(Invitation::class)->state('unconfirmed')->create([
            'created_at' => now()->addDays(5),
        ]);

        $response = $this->acceptInvitationWithoutSignature($invitation);

        $this->assertSame(403, $response->status());
        $this->assertNull($invitation->fresh()->confirmed_at);
        $this->assertFalse($user->is($invitation->cosplayer->user));
    }

    private function acceptInvitation(Invitation $invitation): TestResponse
    {
        $url = URL::temporarySignedRoute(
            'invitations.show', now()->addDays(15), compact('invitation')
        );

        return $this->get($url);
    }

    private function acceptInvitationWithoutSignature(Invitation $invitation): TestResponse
    {
        return $this->get('/invitations/'.$invitation->id);
    }
}
