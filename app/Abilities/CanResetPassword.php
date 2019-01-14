<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Abilities;

use App\Jobs\ResetPassword as ResetPasswordNotification;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordBase;

trait CanResetPassword
{
    use CanResetPasswordBase;

    /**
     * Send the password reset notification.
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
