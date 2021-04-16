<?php

declare(strict_types=1);

namespace App\Abilities;

use App\Notifications\ResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordBase;

trait CanResetPassword
{
    use CanResetPasswordBase;

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify((new ResetPassword($token))->onQueue('emails'));
    }
}
