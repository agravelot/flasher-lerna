<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Cosplayer\Tests\Feature;

use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class DeleteCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_a_cosplayer(): void
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->deleteCosplayer($cosplayer);

        $response->assertStatus(204);
        $this->assertNull($cosplayer->fresh());
    }

    public function deleteCosplayer(Cosplayer $cosplayer): TestResponse
    {
        return $this->json('delete', "/api/admin/cosplayers/{$cosplayer->slug}");
    }

    public function test_user_cannot_delete_a_cosplayer(): void
    {
        $this->actingAsUser();
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->deleteCosplayer($cosplayer);

        $response->assertStatus(403);
        $this->assertNotNull($cosplayer->fresh());
    }

    public function test_guest_cannot_delete_a_cosplayer(): void
    {
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->deleteCosplayer($cosplayer);

        $response->assertStatus(401);
        $this->assertNotNull($cosplayer->fresh());
    }
}
