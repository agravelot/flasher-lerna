<?php

namespace Tests\Feature\Http\Controller\Admin\Cosplayer;

use App\Models\Cosplayer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_cosplayer_with_new_related_user()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();
        $this->assertNull($cosplayer->user);

        $cosplayer->user = factory(User::class)->create();
        $response = $this->update($cosplayer);

        $response->assertOk();
        $this->assertNotNull($cosplayer->fresh()->user);
        $response->assertJson($this->getCosplayerJson($cosplayer->fresh()));
    }

    private function update(Cosplayer $cosplayer, UploadedFile $avatar = null, bool $withAvatar = true): TestResponse
    {
        $params = [
            'name' => $cosplayer->name,
            'description' => $cosplayer->description,
            'user_id' => optional($cosplayer->user)->id,
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

    public function test_admin_can_update_cosplayer_with_updated_related_user()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create(['user_id' => factory(User::class)->create()->id]);
        $this->assertNotNull($cosplayer->user);

        $cosplayer->user = factory(User::class)->create();
        $response = $this->update($cosplayer);

        $response->assertOk();
        $this->assertNotNull($cosplayer->fresh()->user);
        $response->assertJson($this->getCosplayerJson($cosplayer->fresh()));
    }

    public function test_admin_can_update_cosplayer_with_new_avatar()
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

    public function test_admin_can_update_cosplayer_and_remove_avatar()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->state('avatar')->create();
        $this->assertNotNull($cosplayer->avatar);

        $response = $this->update($cosplayer, null);

        $response->assertOk();
        $response->assertJson($this->getCosplayerJson($cosplayer->fresh()));
        $this->assertNull($cosplayer->fresh()->avatar);
    }

    public function testAdminCanUpdateCosplayerWithSameName()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();

        $cosplayer->description = '42';
        $response = $this->update($cosplayer);

        $cosplayer = Cosplayer::latest()->first();
        $response->assertOk();
        $response->assertJson($this->getCosplayerJson($cosplayer));
    }

    public function test_admin_can_update_cosplayer_name_and_update_slug()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();

        $cosplayer->name = $name = 'Name test';
        $response = $this->update($cosplayer);

        $this->assertSame(Str::slug($name), $cosplayer->fresh()->slug);
        $response->assertOk()
            ->assertJson($this->getCosplayerJson($cosplayer->fresh()));
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
