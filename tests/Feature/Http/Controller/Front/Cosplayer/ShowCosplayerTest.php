<?php

namespace Tests\Feature\Http\Controller\Front\Cosplayer;

use Tests\TestCase;
use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_a_cosplayer()
    {
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->showCosplayers($cosplayer->slug);

        $response->assertStatus(200);
        $response->assertSee($cosplayer->name);
        $response->assertSee($cosplayer->description);
    }

    private function showCosplayers(string $slug): TestResponse
    {
        return $this->get('/cosplayers/'.$slug);
    }

    public function test_guest_can_not_view_an_unknown_cosplayer()
    {
        $response = $this->showCosplayers('some-random-slug');

        $response->assertStatus(404);
    }
}
