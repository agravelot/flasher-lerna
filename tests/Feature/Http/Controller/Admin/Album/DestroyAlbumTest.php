<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Tests\Feature\Http\Controller\Admin\Album;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class DestroyAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_destroy_a_album()
    {
        $this->actingAsAdmin();

        /* @var Album $album */
        $album = factory(Album::class)->create();

        $response = $this->deleteAlbum($album->slug);

        $this->assertSame(0, Album::count());
        $response->assertStatus(302);
        $response->assertRedirect('/admin/albums');
        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertSee('Album successfully deleted')
            ->assertDontSee($album->title)
            ->assertDontSee($album->body);
    }

    private function deleteAlbum(string $slug): TestResponse
    {
        return $this->delete('/admin/albums/' . $slug);
    }

    public function test_user_can_not_destroy_a_album()
    {
        $this->actingAsUser();

        /* @var Album $album */
        $album = factory(Album::class)->create();

        $response = $this->deleteAlbum($album->slug);

        $this->assertSame(1, Album::count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_destroy_a_album_and_is_redirected_to_login()
    {
        /* @var Album $album */
        $album = factory(Album::class)->state('withUser')->create();

        $response = $this->deleteAlbum($album->slug);

        $this->assertSame(1, Album::count());
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
