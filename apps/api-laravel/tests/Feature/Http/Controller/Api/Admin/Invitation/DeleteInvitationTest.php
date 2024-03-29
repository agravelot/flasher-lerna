<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\Admin\Invitation;

use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeleteInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_invitations(): void
    {
        Mail::fake();
        /** @var Collection $invitations */
        $invitation = factory(Invitation::class)->create();
        $this->actingAsAdmin();

        $response = $this->deleteInvitation($invitation);

        $response->assertSuccessful();
        $this->assertCount(0, Invitation::all());
    }

    public function test_user_cannot_delete_invitations(): void
    {
        Mail::fake();
        $this->actingAsUser();
        $invitation = factory(Invitation::class)->create();

        $response = $this->deleteInvitation($invitation);

        $this->assertSame(403, $response->status());
    }

    public function test_guest_cannot_delete_invitations(): void
    {
        Mail::fake();
        $invitation = factory(Invitation::class)->create();

        $response = $this->deleteInvitation($invitation);

        $this->assertSame(401, $response->status());
    }

    public function deleteInvitation(Invitation $invitation): TestResponse
    {
        return $this->json(
            'DELETE',
            "/api/admin/invitations/{$invitation->id}"
        );
    }
}
