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

    public function test_invited_user_can_accept_invitation_and_cosplayer_is_linked_to_user()
    {
        Mail::fake();
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $invitation = factory(Invitation::class)->create();

        $response = $this->acceptInvitation($invitation);

        $response->assertOk();
        $this->assertNotNull($invitation->fresh()->confirmed_at);
        $this->assertSame($user, $invitation->cosplayer->user);
    }

    public function test_unauthenticated_can_not_accept_invitation()
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
