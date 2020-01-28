<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class InvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Invitation
     */
    public $invitation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->markdown('emails.invitations.invitation')
            ->text('emails.invitations.invitation_txt')
            ->subject("{$this->invitation->cosplayer->name}: Invitation pour récupérer à vos 📸 photos")
            ->with([
                'temporaryInvitationUrl' => URL::temporarySignedRoute(
                    'invitations.show', now()->addDays(15), ['invitation' => $this->invitation]
                ),
            ]);
    }
}
