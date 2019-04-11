<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Auth;

use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Jobs\VerifyEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register()
    {
        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);
        $user = factory(User::class)->make(['password' => 'secret']);
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'secret',
            'password_confirmation' => 'secret',
            'g-recaptcha-response' => '1',
        ];
        Mail::assertNothingQueued();
        Queue::assertNothingPushed();

        $response = $this->post('/register', $data);

        $response->assertRedirect('/');
        $this->followRedirects($response)
            ->assertStatus(200);
//        Queue::assertPushedOn('emails', VerifyEmail::class);
//        Mail::assertQueued(VerifyEmail::class, function ($mail) use ($user) {
//            return $mail->hasTo($user->email);
//        });
    }

    protected function setUp(): void
    {
        parent::setUp();
        session()->setPreviousUrl('/register');
        Mail::fake();
        Queue::fake();
    }
}
