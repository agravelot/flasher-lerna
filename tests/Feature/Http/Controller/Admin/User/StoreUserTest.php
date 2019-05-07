<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\User;

use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreUserTest extends TestCase
{
    use RefreshDatabase;

    const USER_DATA = [
        'name' => 'A user name',
        'email' => 'mail@company.com',
        'password' => 'secret',
        'password_confirmation' => 'secret',
        'cosplayer' => null,
        'g-recaptcha-response' => '1',
    ];

    public function test_admin_can_store_a_user()
    {
        $this->actingAsAdmin();

        $response = $this->storeUser(self::USER_DATA);

        $latestUser = User::latest()->first();
        $this->assertSame(self::USER_DATA['name'], $latestUser->name);
        $this->assertSame(self::USER_DATA['email'], $latestUser->email);
        $response->assertStatus(302)
            ->assertRedirect('/admin/users');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee(self::USER_DATA['name'])
            ->assertSee('User successfully created')
            ->assertDontSee(self::USER_DATA['email']);
    }

    private function storeUser(array $data): TestResponse
    {
        session()->setPreviousUrl('/admin/users/create');

        return $this->post('/admin/users', $data);
    }

    public function test_admin_can_not_create_two_users_with_the_same_name_and_redirect_with_error()
    {
        $this->actingAsAdminNotStored();
        factory(User::class)->create(['name' => self::USER_DATA['name']]);

        $response = $this->storeUser(self::USER_DATA);

        $this->assertSame(1, User::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/users/create');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee('The name has already been taken.');
    }

    public function test_admin_can_not_create_two_users_with_the_same_email_and_redirect_with_error()
    {
        $this->actingAsAdminNotStored();
        factory(User::class)->create(['email' => self::USER_DATA['email']]);

        $response = $this->storeUser(self::USER_DATA);

        $this->assertSame(1, User::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/users/create');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee('The email has already been taken.');
    }

    public function test_user_can_not_store_a_user()
    {
        $this->actingAsUser();

        $response = $this->storeUser(self::USER_DATA);

        $this->assertNull(User::where('name', self::USER_DATA['name'])->where('email', self::USER_DATA['email'])->first());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_store_a_user_and_is_redirected_to_login()
    {
        $response = $this->storeUser(self::USER_DATA);

        $this->assertSame(0, User::count());
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
