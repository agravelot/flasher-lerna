<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Admin\Cosplayer;

use App\Http\Resources\MediaResource;
use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanStoreCosplayers(): void
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->make();

        $response = $this->storeCosplayer($cosplayer);

        $cosplayer = Cosplayer::latest()->first();
        $response->assertCreated()
            ->assertJson([
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

    public function testAdminCanStoreCosplayerWithPicture(): void
    {
        $this->actingAsAdmin();
        /** @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->make();
        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->storeCosplayer($cosplayer, $avatar);

        $cosplayer = Cosplayer::latest()->first();
        $response->assertCreated()
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

    public function testUserCannotStoreCosplayers(): void
    {
        $this->actingAsUser();
        $cosplayer = factory(Cosplayer::class)->make();

        $response = $this->storeCosplayer($cosplayer);

        $response->assertStatus(403);
        $this->assertSame(0, Cosplayer::count());
    }

    public function testGuestCannotStoreCosplayers(): void
    {
        $cosplayer = factory(Cosplayer::class)->make();

        $response = $this->storeCosplayer($cosplayer);

        $response->assertStatus(401);
        $this->assertSame(0, Cosplayer::count());
    }
}
