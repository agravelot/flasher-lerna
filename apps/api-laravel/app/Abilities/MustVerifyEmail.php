<?php

declare(strict_types=1);

namespace App\Abilities;

use App\Notifications\VerifyEmail;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailBase;

trait MustVerifyEmail
{
    use MustVerifyEmailBase;

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify((new VerifyEmail())->onQueue('emails'));
    }
}
