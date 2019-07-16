<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Jobs\VerifyEmail;
use Modules\Core\Entities\Setting;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_verification_from_settings_to_user_email()
    {
        Notification::fake();
        /** @var User $user */
        $user = factory(User::class)->create();
        $user->email_verified_at = null;
        $user->save();
        /** @var Setting $setting */
        $setting = Setting::where('name', 'email_from')->first();
        $setting->value = 'test@email.com';
        $setting->save();

        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user, VerifyEmail::class,
            function (VerifyEmail $notification, array $channels) use ($user, $setting) {
                $this->assertContains($setting->value, $notification->toMail($user)->from);

                return true;
            });
    }
}
