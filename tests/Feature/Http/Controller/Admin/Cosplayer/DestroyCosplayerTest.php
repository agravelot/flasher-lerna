<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Front\Cosplayer;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class DestroyCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_destroy_a_cosplayer()
    {
        $this->actingAsAdmin();

        /* @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->deleteCosplayer($cosplayer->slug);

        $this->assertSame(0, Cosplayer::count());
        $response->assertStatus(302);
        $response->assertRedirect('/admin/cosplayers');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee('Cosplayer successfully deleted')
            ->assertDontSee($cosplayer->name)
            ->assertDontSee($cosplayer->description);
    }

    private function deleteCosplayer(string $slug): TestResponse
    {
        return $this->delete('/admin/cosplayers/' . $slug);
    }

    public function test_user_can_not_destroy_a_cosplayer()
    {
        $this->actingAsUser();

        /* @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->deleteCosplayer($cosplayer->slug);

        $this->assertSame(1, Cosplayer::count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_destroy_a_cosplayer_and_is_redirected_to_login()
    {
        /* @var Cosplayer $cosplayer */
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->deleteCosplayer($cosplayer->slug);

        $this->assertSame(1, Cosplayer::count());
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
