<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Admin\Cosplayer;

use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_cosplayer_with_new_related_user(): void
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();
        $this->assertNull($cosplayer->sso_id);

        $cosplayer->sso_id = factory(Cosplayer::class)->states(['withUser'])->make()->sso_id;
        $response = $this->update($cosplayer);

        $response->assertOk();
        $this->assertNotNull($cosplayer->fresh()->sso_id);
        $response->assertJson($this->getCosplayerJson($cosplayer->fresh()));
    }

    private function update(Cosplayer $cosplayer, UploadedFile $avatar = null, bool $withAvatar = true): TestResponse
    {
        $params = [
            'name' => $cosplayer->name,
            'description' => $cosplayer->description,
            'sso_id' => $cosplayer->sso_id,
        ];

        if ($withAvatar) {
            $params['avatar'] = $avatar;
        }

        return $this->json('patch', "/api/admin/cosplayers/{$cosplayer->slug}", $params);
    }

    private function getCosplayerJson(Cosplayer $cosplayer): array
    {
        return [
            'data' => [
                'id' => $cosplayer->id,
                'name' => $cosplayer->name,
                'slug' => $cosplayer->slug,
                'description' => $cosplayer->description,
                'created_at' => $cosplayer->created_at->jsonSerialize(),
                'updated_at' => $cosplayer->updated_at->jsonSerialize(),
            ],
        ];
    }

    public function test_admin_can_update_cosplayer_with_updated_related_user(): void
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->states(['withUser'])->create();
        $this->assertNotNull($cosplayer->sso_id);

        $cosplayer->sso_id = Str::uuid()->serialize();
        $response = $this->update($cosplayer);

        $response->assertOk();
        $this->assertNotNull($cosplayer->fresh()->sso_id);
        $response->assertJson($this->getCosplayerJson($cosplayer->fresh()));
    }

    public function test_admin_can_update_cosplayer_with_new_avatar(): void
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();
        $this->assertNull($cosplayer->avatar);

        $avatar = UploadedFile::fake()->image('fake.jpg');
        $response = $this->update($cosplayer, $avatar);

        $response->assertOk();
        $this->assertNotNull($cosplayer->fresh()->avatar);
        $response->assertJson($this->getCosplayerJson($cosplayer->fresh()));
    }

    public function test_admin_can_update_cosplayer_and_remove_avatar(): void
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->state('avatar')->create();
        $this->assertNotNull($cosplayer->avatar);

        $response = $this->update($cosplayer, null);

        $response->assertOk();
        $response->assertJson($this->getCosplayerJson($cosplayer->fresh()));
        $this->assertNull($cosplayer->fresh()->avatar);
    }

    public function testAdminCanUpdateCosplayerWithSameName(): void
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();

        $cosplayer->description = '42';
        $response = $this->update($cosplayer);

        $cosplayer = Cosplayer::latest()->first();
        $response->assertOk();
        $response->assertJson($this->getCosplayerJson($cosplayer));
    }

    public function test_admin_can_update_cosplayer_name_and_update_slug(): void
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();

        $cosplayer->name = $name = 'Name test';
        $response = $this->update($cosplayer);

        $this->assertSame(Str::slug($name), $cosplayer->fresh()->slug);
        $response->assertOk()
            ->assertJson($this->getCosplayerJson($cosplayer->fresh()));
    }

    public function testUserCannotUpdateCosplayers(): void
    {
        $this->actingAsUser();
        $cosplayer = factory(Cosplayer::class)->create();
        $cosplayer->description = '42';

        $response = $this->update($cosplayer);

        $response->assertStatus(403);
        $this->assertNotSame($cosplayer->description, $cosplayer->fresh()->description);
    }

    public function testGuestCannotUpdateCosplayers(): void
    {
        $cosplayer = factory(Cosplayer::class)->create();
        $cosplayer->description = '42';

        $response = $this->update($cosplayer);

        $response->assertStatus(401);
        $this->assertNotSame($cosplayer->description, $cosplayer->fresh()->description);
    }
}
