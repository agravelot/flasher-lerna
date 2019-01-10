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

class StoreCosplayerTest extends TestCase
{
    use RefreshDatabase;

    const COSPLAYER_DATA = [
        'name' => 'A cosplayer name',
        'description' => 'A random description',
    ];

    public function test_admin_can_store_a_cosplayer()
    {
        $this->actingAsAdmin();

        $response = $this->storeCosplayer(self::COSPLAYER_DATA);

        $this->assertSame(1, Cosplayer::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/cosplayers');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee(self::COSPLAYER_DATA['name'])
            ->assertSee('Cosplayer successfully added')
            ->assertDontSee(self::COSPLAYER_DATA['description']);
    }

    private function storeCosplayer(array $data): TestResponse
    {
        session()->setPreviousUrl('/admin/cosplayers/create');

        return $this->post('/admin/cosplayers', $data);
    }

    public function test_admin_can_not_create_two_cosplayers_with_the_same_name_and_redirect_with_error()
    {
        $this->actingAsAdmin();

        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->storeCosplayer(['name' => $cosplayer->name]);

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

        $response = $this->storeCosplayer(self::COSPLAYER_DATA);

        $this->assertSame(0, Cosplayer::count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_store_a_cosplayer_and_is_redirected_to_login()
    {
        $response = $this->storeCosplayer(self::COSPLAYER_DATA);

        $this->assertSame(0, Cosplayer::count());
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
