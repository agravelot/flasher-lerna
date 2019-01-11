<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Cosplayer;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class UpdateCosplayerTest extends TestCase
{
    use RefreshDatabase;

    const COSPLAYER_DATA = [
        'name' => 'A cosplayer name',
        'description' => 'A random description',
    ];

    public function test_admin_can_update_a_cosplayer()
    {
        $this->actingAsAdmin();
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->updateCosplayer(self::COSPLAYER_DATA, $cosplayer);

        $this->assertSame(1, Cosplayer::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/cosplayers');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee(self::COSPLAYER_DATA['name'])
            ->assertDontSee(self::COSPLAYER_DATA['description']);
    }

    private function updateCosplayer(array $data, Cosplayer $cosplayer): TestResponse
    {
        session()->setPreviousUrl('/admin/cosplayers/' . $cosplayer->slug . '/edit');

        return $this->patch('/admin/cosplayers/' . $cosplayer->slug, $data);
    }

    public function test_admin_can_update_a_categories_with_the_same_name()
    {
        $this->actingAsAdmin();
        $category = factory(Cosplayer::class)->create();

        $response = $this->updateCosplayer([
            'name' => $category->name,
            'description' => 'updated description',
        ], $category);

        $this->assertSame(1, Cosplayer::count());
        $response->assertStatus(302)
            ->assertRedirect('/admin/cosplayers');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee($category->name)
            ->assertSee('Cosplayer successfully updated')
            ->assertDontSee('updated description')
            ->assertDontSee('The name has already been taken.');
    }

    public function test_user_can_not_update_a_cosplayer()
    {
        $this->actingAsUser();
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->updateCosplayer(self::COSPLAYER_DATA, $cosplayer);

        $this->assertSame(1, Cosplayer::count());
        $this->assertSame($cosplayer->id, $cosplayer->fresh()->id);
        $this->assertSame($cosplayer->title, $cosplayer->fresh()->title);
        $this->assertSame($cosplayer->description, $cosplayer->fresh()->description);
        $response->assertStatus(403);
    }

    public function test_guest_can_not_update_a_cosplayer_and_is_redirected_to_login()
    {
        $cosplayer = factory(Cosplayer::class)->create();

        $response = $this->updateCosplayer(self::COSPLAYER_DATA, $cosplayer);

        $this->assertSame(1, Cosplayer::count());
        $this->assertSame($cosplayer->id, $cosplayer->fresh()->id);
        $this->assertSame($cosplayer->title, $cosplayer->fresh()->title);
        $this->assertSame($cosplayer->description, $cosplayer->fresh()->description);
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
