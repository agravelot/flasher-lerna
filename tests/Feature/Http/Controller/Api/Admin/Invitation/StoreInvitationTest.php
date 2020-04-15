<?php

namespace Tests\Feature\Http\Controller\Api\Admin\Invitation;

use App\Mail\InvitationMail;
use App\Models\Cosplayer;
use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StoreInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_send_invitation_to_cosplayers(): void
    {
        Mail::fake();
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();
        $invitation = factory(Invitation::class)->make([
            'cosplayer_id' => $cosplayer->id,
        ]);

        $response = $this->storeInvitation($invitation);

        $response->assertCreated()
            ->assertJson([
                'data' => [
                    'email' => $invitation->email,
                    'cosplayer' => [
                        'id' => $invitation->cosplayer->id,
                        //..
                    ],
                    'message' => $invitation->message,
                ],
            ]);
        $this->assertCount(1, Invitation::all());
        Mail::assertQueued(InvitationMail::class,
            static function (Mailable $mail) use ($cosplayer) {
                return $mail->invitation->cosplayer->is($cosplayer);
            });
        // Assert a message was sent to the given users...
        Mail::assertQueued(InvitationMail::class,
            static function (Mailable $mail) use ($invitation) {
                return $mail->hasTo($invitation->email);
            });
        Mail::assertQueued(InvitationMail::class, 1);
    }

    public function test_user_can_not_share_album(): void
    {
        Mail::fake();
        $this->actingAsUser();

        $response = $this->storeInvitation(factory(Invitation::class)->make());

        $response->assertStatus(403);
        Mail::assertNothingSent();
    }

    public function test_guest_can_not_share_album(): void
    {
        Mail::fake();

        $response = $this->storeInvitation(factory(Invitation::class)->make());

        $response->assertStatus(401);
        Mail::assertNothingSent();
    }

    public function test_admin_cannot_share_with_a_contact_with_empty_email(): void
    {
        Mail::fake();
        $this->actingAsAdmin();
        $invitation = factory(Invitation::class)->make([
            'email' => '',
        ]);

        $response = $this->storeInvitation($invitation);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
        Mail::assertNothingSent();
    }

    public function test_admin_cannot_share_with_a_contact_without_email(): void
    {
        Mail::fake();
        $this->actingAsAdmin();
        $invitation = factory(Invitation::class)->make([
            'email' => null,
        ]);

        $response = $this->storeInvitation($invitation);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
        Mail::assertNothingSent();
    }

    public function test_admin_cannot_share_with_a_contact_with_invalid_email(): void
    {
        Mail::fake();
        $this->actingAsAdmin();
        $invitation = factory(Invitation::class)->make([
            'email' => 'malfomedemail@',
        ]);

        $response = $this->storeInvitation($invitation);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
        Mail::assertNothingSent();
    }

    public function test_admin_cannot_share_with_a_message_with_empty_message(): void
    {
        Mail::fake();
        $this->actingAsAdmin();
        $invitation = factory(Invitation::class)->make([
            'message' => '',
        ]);

        $response = $this->storeInvitation($invitation);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('message');
        Mail::assertNothingSent();
    }

    public function test_if_cosplayer_not_found_return_error_and_email_not_send(): void
    {
        Mail::fake();
        $this->actingAsAdmin();
        $invitation = factory(Invitation::class)->make([
            'cosplayer_id' => 4242,
        ]);

        $response = $this->storeInvitation($invitation);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('cosplayer_id');

        Mail::assertNothingQueued(InvitationMail::class);
    }

    private function storeInvitation(Invitation $invitation): TestResponse
    {
        return $this->json('post', '/api/admin/invitations', [
            'email' => $invitation->email,
            'cosplayer_id' => $invitation->cosplayer_id,
            'message' => $invitation->message,
        ]);
    }
}
