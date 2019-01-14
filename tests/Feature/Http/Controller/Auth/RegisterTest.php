<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Auth;

use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        session()->setPreviousUrl('/register');
    }

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

        $response = $this->post('/register', $data);

        $response->assertRedirect('/');
        $this->followRedirects($response)
            ->assertStatus(200);
    }
}
