<?php

namespace Tests\Feature\Http\Controller\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_user()
    {
        $excepted = 'randomName';
        $this->actingAsAdmin();
        /** @var User $user */
        $user = factory(User::class)->create();

        $user->name = $excepted;
        $response = $this->updateUser($user);

        $response->assertOk();
        $this->assertSame($excepted, $user->fresh()->name);
        $this->assertJsonUserFragment($response, $user);
    }

    private function updateUser(User $user, ?string $newPassword = null): TestResponse
    {
        return $this->json('patch', "/api/admin/users/{$user->id}", [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);
    }

    private function assertJsonUserFragment(TestResponse $response, User $user): void
    {
        $response->assertJsonFragment([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified_at' => $user->email_verified_at,
                'actions' => ['impersonate' => route('impersonate', $user)],
            ],
        ]);
    }

    public function test_admin_can_update_user_password()
    {
        $excepted = 'password';
        $this->actingAsAdmin();
        /** @var User $user */
        $user = factory(User::class)->create();

        $response = $this->updateUser($user, $excepted);

        $response->assertOk();
        $this->assertTrue(Hash::check($excepted, $user->fresh()->password));
        $this->assertJsonUserFragment($response, $user);
    }

    public function test_admin_can_update_other_user_role()
    {
        $this->actingAsAdmin();
        /** @var User $user */
        $user = factory(User::class)->state('user')->create();

        $user->role = 'admin';
        $response = $this->updateUser($user);

        $response->assertOk();
        $this->assertSame('admin', $user->fresh()->role);
        $this->assertJsonUserFragment($response, $user);
    }

    public function test_admin_can_not_update_other_role_as_null()
    {
        $this->actingAsAdmin();
        /** @var User $user */
        $user = factory(User::class)->state('user')->create();

        $user->role = null;
        $response = $this->updateUser($user);

        $response->assertStatus(422);
        $this->assertSame('user', $user->fresh()->role);
        $response->assertJsonValidationErrors(['role']);
    }

    public function testUserCanUpdateUser()
    {
        $this->actingAsUser();
        $user = factory(User::class)->create();

        $response = $this->updateUser($user);

        $response->assertStatus(403);
    }

    public function testGuestCanUpdateUser()
    {
        $user = factory(User::class)->create();

        $response = $this->updateUser($user);

        $response->assertStatus(401);
    }
}
