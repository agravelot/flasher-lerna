<?php

namespace Tests\Feature\Http\Controller\Auth;

use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register(): void
    {
        Mail::fake();
        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);
        $user = factory(User::class)->make();
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
            ->assertOk();
        $registeredUser = User::latest()->first();
        $this->assertAuthenticatedAs($registeredUser);
    }

    protected function setUp(): void
    {
        parent::setUp();
        session()->setPreviousUrl('/register');
    }
}
