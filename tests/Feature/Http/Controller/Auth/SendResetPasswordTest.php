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
use Modules\Core\Entities\Setting;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_reset_password_from_settings_to_user_email()
    {
        Notification::fake();
        /** @var User $user */
        $user = factory(User::class)->create();
        /** @var Setting $setting */
        $setting = Setting::where('name', 'email_from')->first();
        $setting->value = 'test@email.com';
        $setting->save();
        $setting = Setting::where('name', 'app_name')->first();
        $setting->value = 'Flasher';
        $setting->save();

        $response = $this->post('password/email', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class,
            function (ResetPassword $notification, array $channels) use ($user, $setting) {
                $this->assertSame('test@email.com', $notification->toMail($user)->from[0]);
                $this->assertSame('Flasher', $notification->toMail($user)->from[1]);

                return true;
            });
    }
}
