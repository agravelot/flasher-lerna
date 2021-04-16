<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Front\Cosplayer;

use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class IndexCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_view_nothing_to_show(): void
    {
        $response = $this->showCosplayers();

        $response->assertOk();
        $response->assertSee('Nothing to show');
    }

    private function showCosplayers(): TestResponse
    {
        return $this->get('/cosplayers');
    }

    public function test_guest_can_view_published_albums(): void
    {
        $cosplayers = factory(Cosplayer::class, 2)->create();

        $response = $this->showCosplayers();

        $response->assertOk();
        $response->assertSee($cosplayers->get(0)->name);
        $response->assertDontSee($cosplayers->get(0)->description);
        $response->assertSee($cosplayers->get(1)->name);
        $response->assertDontSee($cosplayers->get(1)->description);
    }
}