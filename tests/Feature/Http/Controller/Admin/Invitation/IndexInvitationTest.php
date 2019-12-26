<?php

namespace Tests\Feature\Http\Controller\Admin\Cosplayer;

use App\Models\Invitation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class IndexInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_index_invitations(): void
    {
        Mail::fake();
        $this->actingAsAdmin();
        $invitations = factory(Invitation::class, 8)->create();

        $response = $this->getInvitations();

        $response->assertOk();
        $this->assertJsonUserFragment($response, $invitations);
    }

    private function getInvitations(): TestResponse
    {
        return $this->json('get', '/api/admin/invitations');
    }

    private function assertJsonUserFragment(TestResponse $response, Collection $invitations): void
    {
        $invitations->each(static function (Invitation $invitation) use ($response) {
            $response->assertJsonFragment([
                'id' => $invitation->id,
                'email' => $invitation->email,
                //'cosplayer' => new CosplayerResource($this->cosplayer),
                'message' => $invitation->message,
                'confirmed_at' => $invitation->confirmed_at,
                'created_at' => $invitation->created_at,
                'updated_at' => $invitation->updated_at,
            ]);
        });
    }

    public function test_user_can_not_index_invitations(): void
    {
        Mail::fake();
        $this->actingAsUser();
        $invitations = factory(Invitation::class, 8)->create();

        $response = $this->getInvitations();

        $response->assertStatus(403);
    }

    public function test_guest_can_index_invitaitons(): void
    {
        Mail::fake();
        $invitations = factory(Invitation::class, 8)->create();

        $response = $this->getInvitations();

        $response->assertStatus(401);
    }
}
