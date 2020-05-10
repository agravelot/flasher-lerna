<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Admin\Cosplayer;

use App\Models\Cosplayer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class IndexCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanIndexCosplayers()
    {
        $this->actingAsAdmin();
        $cosplayers = factory(Cosplayer::class, 8)->create();

        $response = $this->getCosplayers();

        $response->assertOk();
        $this->assertJsonUserFragment($response, $cosplayers);
    }

    private function getCosplayers(): TestResponse
    {
        return $this->json('get', '/api/admin/cosplayers');
    }

    private function assertJsonUserFragment(TestResponse $response, Collection $cosplayers): void
    {
        $cosplayers->each(static function (Cosplayer $cosplayer) use ($response) {
            $response->assertJsonFragment([
                'name' => $cosplayer->name,
                'slug' => $cosplayer->slug,
            ]);
        });
    }

    public function testUserCannotIndexCosplayers()
    {
        $this->actingAsUser();
        $cosplayers = factory(Cosplayer::class, 8)->create();

        $response = $this->getCosplayers();

        $response->assertStatus(403);
    }

    public function testGuestCannotIndexCosplayers()
    {
        $cosplayers = factory(Cosplayer::class, 8)->create();

        $response = $this->getCosplayers();

        $response->assertStatus(401);
    }
}
