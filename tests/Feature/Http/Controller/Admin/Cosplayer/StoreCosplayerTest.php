<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Cosplayer;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cosplayer;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreCosplayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_a_cosplayer()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->make();

        $response = $this->storeCosplayer($cosplayer);

        $this->assertSame(1, Cosplayer::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/cosplayers');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($cosplayer->name)
            ->assertSee('Cosplayer successfully added')
            ->assertDontSee($cosplayer->description);
    }

    public function test_admin_can_store_a_cosplayer_related_to_an_user()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->make();
        $user = factory(User::class)->create();

        $response = $this->storeCosplayer($cosplayer, ['user_id' => $user->id]);

        $this->assertSame(1, Cosplayer::count());
        $this->assertNotNull(Cosplayer::first()->user);
        $response->assertStatus(302)
            ->assertRedirect('/admin/cosplayers');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($cosplayer->name)
            ->assertSee('Cosplayer successfully added')
            ->assertDontSee($cosplayer->description);
    }

    public function test_admin_can_not_store_a_cosplayer_related_to_an_non_existant_user()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->make();

        $response = $this->storeCosplayer($cosplayer, ['user_id' => 42]);

        $this->assertSame(0, Cosplayer::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/cosplayers/create');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($cosplayer->name)
            ->assertSee('The selected user id is invalid.')
            ->assertSee($cosplayer->description)
            ->assertDontSee('Cosplayer successfully added');
    }

    private function storeCosplayer(Cosplayer $cosplayer, array $data = []): TestResponse
    {
        session()->setPreviousUrl('/admin/cosplayers/create');

        return $this->post('/admin/cosplayers', array_merge($cosplayer->toArray(), $data));
    }

    public function test_admin_can_not_create_two_cosplayers_with_the_same_name_and_redirect_with_error()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create()->replicate();

        $response = $this->storeCosplayer($cosplayer);

        $this->assertSame(1, Cosplayer::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/cosplayers/create');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee(' The name has already been taken.');
    }

    public function test_user_can_not_store_a_cosplayer()
    {
        $this->actingAsUser();
        $cosplayer = factory(Cosplayer::class)->make();

        $response = $this->storeCosplayer($cosplayer);

        $this->assertSame(0, Cosplayer::count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_store_a_cosplayer_and_is_redirected_to_login()
    {
        $cosplayer = factory(Cosplayer::class)->make();

        $response = $this->storeCosplayer($cosplayer);

        $this->assertSame(0, Cosplayer::count());
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
