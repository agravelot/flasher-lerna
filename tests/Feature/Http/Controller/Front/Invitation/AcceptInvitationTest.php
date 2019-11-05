<?php

namespace Tests\Feature\Http\Controller\Front\DownloadAlbum;

use Tests\TestCase;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AcceptInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_invited_user_can_accept_invitation_and_cosplayer_is_linked_to_user(): void
    {
        Mail::fake();
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $invitation = factory(Invitation::class)->create();

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
        $invitation = factory(Invitation::class)->state('confirmed')->create();

        $response = $this->acceptInvitation($invitation);

        $this->assertSame(403, $response->status());
        $this->assertSame($invitation->confirmed_at->toString(), $invitation->fresh()->confirmed_at->toString());
    }

    public function test_unauthenticated_can_not_accept_invitation_and_is_redirected_to_login(): void
    {
        Mail::fake();
        $invitation = factory(Invitation::class)->create();

        $response = $this->acceptInvitation($invitation);

        $response->assertRedirect('/login');
        $this->followRedirects($response)
            ->assertOk();
    }

    private function acceptInvitation(Invitation $invitation): TestResponse
    {
        return $this->get('/invitations/'.$invitation->token);
    }
}
