<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        session()->setPreviousUrl('/login');
    }

    public function test_guest_can_login()
    {
        $user = factory(User::class)->create(['password' => 'secret']);
        $data = [
            'email' => $user->email,
            'password' => 'secret',
        ];

        $response = $this->post('/login', $data);

        $response->assertRedirect('/');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($user->name);
    }
}
