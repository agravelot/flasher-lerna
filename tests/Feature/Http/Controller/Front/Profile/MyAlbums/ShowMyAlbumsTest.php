<?php

namespace Tests\Feature\Http\Controller\Front\Profile\MyAlbums;

use Tests\TestCase;
use App\Models\User;
use App\Models\Album;
use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowMyAlbumsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_show_his_albums(): void
    {
        $this->disableExceptionHandling();
        $this->actingAsAdmin();

        $response = $this->getMyAlbums();

        $response->assertOk()->assertSee('Nothing to show');
    }

    public function test_user_can_show_his_albums_without_linked_cosplayer(): void
    {
        $this->actingAsUser();

        $response = $this->getMyAlbums();

        $response->assertOk()->assertSee('Nothing to show');
    }

    public function test_user_can_show_his_albums_with_linked_cosplayer_and_no_albums(): void
    {
        $user = factory(User::class)->create();
        $cosplayer = factory(Cosplayer::class)->create([
            'user_id' => $user->id,
        ]);
        $this->actingAs($user);

        $response = $this->getMyAlbums();

        $response->assertOk()->assertSee('Nothing to show');
    }

    public function test_user_can_show_his_albums_with_linked_cosplayer_and_albums(): void
    {
        $user = factory(User::class)->create();
        /** @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->create([
            'user_id' => $user->id,
        ]);
        $albums = factory(Album::class, 5)->make([
            'user_id' => factory(User::class)->create()->id,
        ]);
        $cosplayer->albums()->saveMany($albums);
        $this->actingAs($user);

        $response = $this->getMyAlbums();

        $response->assertOk()
            ->assertDontSee('Nothing to show')
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_guest_can_no_show_his_albums(): void
    {
        $response = $this->getMyAlbums();

        $response->assertRedirect('/login');
    }

    private function getMyAlbums(): TestResponse
    {
        return $this->get('/profile/my-albums');
    }
}
