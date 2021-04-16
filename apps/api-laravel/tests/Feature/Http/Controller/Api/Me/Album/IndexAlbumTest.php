<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api\Me\Album;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_only_index_his_albums(): void
    {
        factory(Album::class, 5)->create();
        $this->actingAsUser();
        $cosplayer = factory(Cosplayer::class)->create(['sso_id' => auth()->id()]);
        $albums = factory(Album::class, 5)->states(['published'])->create();
        $albums->each(static function (Album $a) use ($cosplayer): void {
            $a->cosplayers()->attach($cosplayer);
            $a->save();
        });

        $response = $this->getJson('/api/me/albums');

        $response->assertStatus(200)->assertJsonCount(5, 'data');
    }

    /** @test */
    public function user_user_no_linked_will_get_empty_result_with_pagination(): void
    {
        $this->withoutExceptionHandling();
        factory(Album::class, 5)->create();
        $this->actingAsUser();

        $response = $this->getJson('/api/me/albums');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0);
    }

    public function test_non_verified_user_cannot_index_his_albums(): void
    {
        $user = factory(User::class)->state('user')->make(['email_verified' => false]);
        $this->actingAs($user, 'api');
        $this->actingAs($user, 'web');

        $response = $this->getJson('/api/me/albums');

        $response->assertStatus(403)
            ->assertJsonPath('message', 'Your email address is not verified.');
    }

    public function test_guest_cannot_index_albums(): void
    {
        $response = $this->getJson('/api/me/albums');

        $response->assertStatus(401);
    }
}
