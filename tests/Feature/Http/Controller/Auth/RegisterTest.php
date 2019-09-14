<?php

namespace Tests\Feature\Http\Controller\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register()
    {
        Setting::where('name', 'email_from')->first()->update(['value'=> 'test@jkanda.fr']);
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

        $response = $this->post('/register', $data);

        $response->assertRedirect('/');
        $this->followRedirects($response)
            ->assertStatus(200);
        //TODO Assert Notification sent
    }

    protected function setUp(): void
    {
        parent::setUp();
        session()->setPreviousUrl('/register');
    }
}
