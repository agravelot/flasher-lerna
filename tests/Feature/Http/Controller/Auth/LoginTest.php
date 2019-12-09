<?php

namespace Tests\Feature\Http\Controller\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        session()->setPreviousUrl('/login');
    }

    public function test_guest_can_login(): void
    {
        $user = factory(User::class)->create(['password' => 'secret']);
        $data = [
            'email' => $user->email,
            'password' => 'secret',
        ];

        $response = $this->post('/login', $data);

        $response->assertRedirect('/');
        $this->followRedirects($response)
            ->assertOk()
            ->assertSee($user->name);
    }
}
