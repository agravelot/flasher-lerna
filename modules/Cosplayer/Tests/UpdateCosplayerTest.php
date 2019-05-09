<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Cosplayer\Tests;

use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class UpdateCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanUpdateCosplayers()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();

        $cosplayer->description = '42';
        $response = $this->update($cosplayer);

        $cosplayer = Cosplayer::latest()->first();
        $response->assertStatus(200);
        $response->assertJson([
            'data' =>
                [
                    'id' => $cosplayer->id,
                    'name' => $cosplayer->name,
                    'slug' => $cosplayer->slug,
                    'description' => '42',
                    'created_at' => $cosplayer->created_at,
                    'updated_at' => $cosplayer->updated_at,
                ],
        ]);
    }

    private function update(Cosplayer $cosplayer): TestResponse
    {
        return $this->json('patch', "/api/admin/cosplayers/{$cosplayer->slug}", [
            'name' => $cosplayer->name,
            'description' => $cosplayer->description,
        ]);
    }

    public function testUserCannotUpdateCosplayers()
    {
        $this->actingAsUser();
        $cosplayer = factory(Cosplayer::class)->create();
        $cosplayer->description = '42';

        $response = $this->update($cosplayer);

        $response->assertStatus(403);
        $this->assertNotSame($cosplayer->description, $cosplayer->fresh()->description);
    }

    public function testGuestCannotUpdateCosplayers()
    {
        $cosplayer = factory(Cosplayer::class)->create();
        $cosplayer->description = '42';

        $response = $this->update($cosplayer);

        $response->assertStatus(401);
        $this->assertNotSame($cosplayer->description, $cosplayer->fresh()->description);
    }
}
