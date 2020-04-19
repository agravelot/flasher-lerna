<?php

namespace Tests\Feature\Http\Controller\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_his_account_and_session_is_terminated_and_removed_from_database_and_user_is_redirected(): void
    {
        $this->actingAsUser();

        $response = $this->deleteUser(auth()->user());

        $response->assertOk();
        $this->assertNull(User::find(auth()->id()));
    }

    public function test_user_cannot_delete_another_account(): void
    {
        $anotherUser = factory(User::class)->create();
        $this->actingAsUser();

        $response = $this->deleteUser($anotherUser);

        $response->assertStatus(403);
        $this->assertNotNull($anotherUser->fresh());
    }

    public function test_guest_cannot_delete_another_account(): void
    {
        $anotherUser = factory(User::class)->create();

        $response = $this->deleteUser($anotherUser);

        $response->assertRedirect('/login');
        $this->assertNotNull($anotherUser->fresh());
    }

    /**
     * @param User|Authenticatable $user
     */
    private function deleteUser($user): TestResponse
    {
        return $this->delete('/api/account/'.$user->id);
    }
}
