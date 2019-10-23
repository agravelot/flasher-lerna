<?php

namespace Tests\Feature\Http\Controller\Api\AdminAlbum;

use Tests\TestCase;
use App\Models\Cosplayer;
use Illuminate\Mail\Mailable;
use App\Mail\CosplayerInvitation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CosplayerInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_sned_invitation_to_cosplayers()
    {
        Mail::fake();
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();
        $contacts = collect([
            [
                'email' => 'john@doe.fr',
                'cosplayer_id' => $cosplayer->id,
            ],
        ]);

        $response = $this->shareToCosplayer($contacts, 'message');

        $response->assertStatus(201);
        $contacts->each(static function ($contact) use ($cosplayer) {
            Mail::assertQueued(CosplayerInvitation::class, static function (Mailable $mail) use ($cosplayer, $contact) {
                return $mail->cosplayer->id === $cosplayer->id;
            });
            // Assert a message was sent to the given users...
            Mail::assertQueued(CosplayerInvitation::class, static function (Mailable $mail) use ($cosplayer, $contact) {
                return $mail->hasTo($contact['email']);
            });
        });
        Mail::assertQueued(CosplayerInvitation::class, $contacts->count());
    }

    public function test_user_can_not_share_album()
    {
        Mail::fake();
        $this->actingAsUser();

        $response = $this->shareToCosplayer(collect([]));

        $response->assertStatus(403);
        Mail::assertNothingSent();
    }

    public function test_guest_can_not_share_album()
    {
        Mail::fake();

        $response = $this->shareToCosplayer(collect());

        $response->assertStatus(401);
        Mail::assertNothingSent();
    }

    public function test_admin_cannot_share_without_contacts()
    {
        Mail::fake();
        $this->actingAsAdmin();
        $contacts = collect();

        $response = $this->shareToCosplayer($contacts);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('contacts');
        Mail::assertNothingSent();
    }

    public function test_admin_cannot_share_with_a_contact_with_empty_email()
    {
        Mail::fake();
        $this->actingAsAdmin();
        $contacts = collect([
            [
                'email' => '',
                'cosplayer_id' => factory(Cosplayer::class)->create()->id,
            ],
        ]);

        $response = $this->shareToCosplayer($contacts);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('contacts.0.email');
        Mail::assertNothingSent();
    }

    public function test_admin_cannot_share_with_a_contact_without_email()
    {
        Mail::fake();
        $this->actingAsAdmin();
        $contacts = collect([
            [
                'cosplayer_id' => factory(Cosplayer::class)->create()->id,
            ],
        ]);

        $response = $this->shareToCosplayer($contacts);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('contacts.0.email');
        Mail::assertNothingSent();
    }

    public function test_admin_cannot_share_with_a_contact_with_invalid_email()
    {
        Mail::fake();
        $this->actingAsAdmin();
        $contacts = collect([
            [
                'email' => 'malfomedemail@',
                'cosplayer_id' => factory(Cosplayer::class)->create()->id,
            ],
        ]);

        $response = $this->shareToCosplayer($contacts);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('contacts.0.email');
        Mail::assertNothingSent();
    }

    public function test_admin_cannot_share_with_a_message_with_empty_message()
    {
        Mail::fake();
        $this->actingAsAdmin();
        $contacts = collect([
            [
                'email' => 'aze@aze.fr',
                'cosplayer_id' => factory(Cosplayer::class)->create()->id,
            ],
        ]);
        $message = '';

        $response = $this->shareToCosplayer($contacts, $message);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('message');
        Mail::assertNothingSent();
    }

    public function test_if_cosplayer_not_found_return_error_and_email_not_send()
    {
        Mail::fake();
        $this->actingAsAdmin();
        $contacts = collect([
            [
                'email' => 'john@doe.fr',
                'cosplayer_id' => 4242,
            ],
        ]);

        $response = $this->shareToCosplayer($contacts, 'message');

        $response->assertStatus(422)
            ->assertJsonValidationErrors('contacts.0.cosplayer_id');

        Mail::assertNothingQueued(CosplayerInvitation::class);
    }

    private function shareToCosplayer(Collection $contacts, string $message = ''): TestResponse
    {
        return $this->json('post', '/api/admin/cosplayer-invitation',
            compact('contacts', 'message')
        );
    }
}
