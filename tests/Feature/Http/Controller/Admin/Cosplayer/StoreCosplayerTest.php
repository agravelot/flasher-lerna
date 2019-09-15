<?php

namespace Tests\Feature\Http\Controller\Admin\Cosplayer;

use Tests\TestCase;
use App\Models\Cosplayer;
use Illuminate\Http\UploadedFile;
use App\Http\Resources\MediaResource;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            'data' => [
                'id' => $cosplayer->id,
                'name' => $cosplayer->name,
                'slug' => $cosplayer->slug,
                'description' => $cosplayer->description,
                //'avatar' => (new MediaResource($cosplayer->avatar))->toArray(request()),
                'created_at' => $cosplayer->created_at->jsonSerialize(),
                'updated_at' => $cosplayer->updated_at->jsonSerialize(),
            ],
        ]);
    }

    private function storeCosplayer(Cosplayer $cosplayer, UploadedFile $avatar = null): TestResponse
    {
        return $this->json('post', '/api/admin/cosplayers', [
            'name' => $cosplayer->name,
            'description' => $cosplayer->description,
            'avatar' => $avatar,
            'user_id' => $cosplayer->user_id,
        ]);
    }

    public function testAdminCanStoreCosplayerWithPicture()
    {
        $this->actingAsAdmin();
        /** @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->make();
        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->storeCosplayer($cosplayer, $avatar);

        $cosplayer = Cosplayer::latest()->first();
        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'id' => $cosplayer->id,
                    'name' => $cosplayer->name,
                    'slug' => $cosplayer->slug,
                    'description' => $cosplayer->description,
                    'avatar' => (new MediaResource($cosplayer->avatar))->toArray(request()),
                    'created_at' => $cosplayer->created_at->jsonSerialize(),
                    'updated_at' => $cosplayer->updated_at->jsonSerialize(),
                ],
            ]);
        $this->assertNotNull($cosplayer->avatar);
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
