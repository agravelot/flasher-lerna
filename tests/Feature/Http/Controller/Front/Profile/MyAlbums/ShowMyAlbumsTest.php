<?php

namespace Tests\Feature\Http\Controller\Front\Profile\MyAlbums;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ShowMyAlbumsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_show_his_albums(): void
    {
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
        $albums = factory(Album::class, 3)->make([
            'user_id' => factory(User::class)->create()->id,
        ]);
        $cosplayer->albums()->saveMany($albums);
        $this->actingAs($user);

        $response = $this->getMyAlbums();

        $response->assertOk()
            ->assertDontSee('Nothing to show')
            ->assertSee($albums->get(0)->title)
            ->assertSee($albums->get(1)->title)
            ->assertSee($albums->get(2)->title);
    }

    public function test_guest_can_no_show_his_albums(): void
    {
        $response = $this->getMyAlbums();

        $response->assertRedirect('/login');
    }

    private function getMyAlbums(): TestResponse
    {
        return $this->get('/account/my-albums');
    }
}
