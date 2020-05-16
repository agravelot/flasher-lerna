<?php

declare(strict_types=1);

namespace App\Observers;

use App\Mail\InvitationMail;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationObserver
{
    public function creating(Invitation $invitation): void
    {
        $invitation->token = Str::random(60);
    }

    public function created(Invitation $invitation): void
    {
        Mail::to($invitation->email)->send(new InvitationMail($invitation));
    }
}
