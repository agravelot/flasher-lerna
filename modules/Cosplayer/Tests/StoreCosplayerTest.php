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
use Illuminate\Http\UploadedFile;
use Modules\Album\Transformers\MediaResource;
use Tests\TestCase;

class StoreCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanStoreCosplayers()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->make();

        $response = $this->storeCosplayer($cosplayer);

        $cosplayer = Cosplayer::latest()->first();
        $response->assertStatus(201);
        $response->assertJson([
            'data' =>
                [
                    'id' => $cosplayer->id,
                    'name' => $cosplayer->name,
                    'slug' => $cosplayer->slug,
                    'description' => $cosplayer->description,
                    'created_at' => $cosplayer->created_at->jsonSerialize(),
                    'updated_at' => $cosplayer->updated_at->jsonSerialize(),
                ],
        ]);
    }

    private function storeCosplayer(Cosplayer $cosplayer): TestResponse
    {
        return $this->json('post', '/api/admin/cosplayers', [
            'name' => $cosplayer->name,
            'description' => $cosplayer->description,
            'avatar' => $cosplayer->avatar,
            'user_id' => $cosplayer->user_id,
        ]);
    }

    public function testAdminCanStoreCosplayerWithPicture()
    {
        $this->actingAsAdmin();
        /** @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->make();
        $cosplayer->avatar = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->storeCosplayer($cosplayer);

        $cosplayer = Cosplayer::latest()->first();
        $this->assertNotNull($cosplayer->getFirstMedia('avatar'));
        $response->assertStatus(201);
        $response->assertJson([
            'data' =>
                [
                    'id' => $cosplayer->id,
                    'name' => $cosplayer->name,
                    'slug' => $cosplayer->slug,
                    'description' => $cosplayer->description,
                    'avatar' => (new MediaResource($cosplayer->getFirstMedia('avatar')))->toArray(request()),
                    'created_at' => $cosplayer->created_at->jsonSerialize(),
                    'updated_at' => $cosplayer->updated_at->jsonSerialize(),
                ],
        ]);
    }

    public function testUserCannotStoreCosplayers()
    {
        $this->actingAsUser();
        $cosplayer = factory(Cosplayer::class)->make();

        $response = $this->storeCosplayer($cosplayer);

        $response->assertStatus(403);
        $this->assertSame(0, Cosplayer::count());
    }

    public function testGuestCannotStoreCosplayers()
    {
        $cosplayer = factory(Cosplayer::class)->make();

        $response = $this->storeCosplayer($cosplayer);

        $response->assertStatus(401);
        $this->assertSame(0, Cosplayer::count());
    }
}
