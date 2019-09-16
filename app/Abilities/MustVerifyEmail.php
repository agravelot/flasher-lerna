<?php

namespace App\Abilities;

use App\Notifications\VerifyEmail;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailBase;

trait MustVerifyEmail
{
    use MustVerifyEmailBase;

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify((new VerifyEmail())->onQueue('emails'));
    }
}
