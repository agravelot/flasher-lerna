<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Front\User;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    const USER_DATA = [
        'name' => 'A user name',
        'email' => 'mail@company.com',
        'password' => 'secret',
        'password_confirmation' => 'secret',
        'cosplayer' => null,
    ];

    public function test_admin_can_update_a_user()
    {
        $this->actingAsAdmin();
        $user = factory(User::class)->create();

        $response = $this->updateUser(self::USER_DATA, $user);

        $this->assertSame(1, User::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/users');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee(self::USER_DATA['name'])
            ->assertDontSee(self::USER_DATA['email']);
    }

    private function updateUser(array $data, User $user): TestResponse
    {
        session()->setPreviousUrl('/admin/users/' . $user->id . '/edit');

        return $this->patch('/admin/users/' . $user->id, $data);
    }

    public function test_admin_can_update_a_users_with_the_same_name()
    {
        $this->actingAsAdmin();
        $user = factory(User::class)->create();

        $response = $this->updateUser(self::USER_DATA, $user);

        $this->assertSame(1, User::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/users');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($user->fresh()->name)
            ->assertSee('User successfully updated')
            ->assertDontSee('updated description')
            ->assertDontSee('The name has already been taken.');
    }

    public function test_user_can_not_update_a_user()
    {
        $this->actingAsUser();
        $user = factory(User::class)->create();

        $response = $this->updateUser(self::USER_DATA, $user);

        $this->assertSame(1, User::count());
        $this->assertSame($user->title, $user->fresh()->title);
        $this->assertSame($user->email, $user->fresh()->email);
        $response->assertStatus(403);
    }

    public function test_guest_can_not_update_a_user_and_is_redirected_to_login()
    {
        $user = factory(User::class)->create();

        $response = $this->updateUser(self::USER_DATA, $user);

        $this->assertSame(1, User::count());
        $this->assertSame($user->title, $user->fresh()->title);
        $this->assertSame($user->email, $user->fresh()->email);
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
