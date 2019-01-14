<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Abilities;

use App\Jobs\VerifyEmail;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailBase;

trait MustVerifyEmail
{
    use MustVerifyEmailBase;

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }
}
