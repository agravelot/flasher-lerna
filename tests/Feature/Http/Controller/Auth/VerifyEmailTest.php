<?php

namespace Tests\Feature\Http\Controller\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;
use App\Notifications\VerifyEmail;
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
        $setting = Setting::where('name', 'app_name')->first();
        $setting->value = 'Flasher';
        $setting->save();

        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user, VerifyEmail::class,
            function (VerifyEmail $notification, array $channels) use ($user, $setting) {
                $this->assertSame('test@email.com', $notification->toMail($user)->from[0]);
                $this->assertSame('Flasher', $notification->toMail($user)->from[1]);

                return true;
            });
    }
}
