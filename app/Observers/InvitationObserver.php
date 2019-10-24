<?php

namespace App\Observers;

use App\Models\Invitation;
use App\Mail\InvitationMail;
use Illuminate\Support\Facades\Mail;

class InvitationObserver
{
    public function created(Invitation $invitation): void
    {
        Mail::to($invitation->email)->send(new InvitationMail($invitation));
    }
}
