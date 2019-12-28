<?php

namespace Tests\Feature\Http\Controller\Api\AdminAlbum;

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

        /** @var Album $album */
        $album = factory(Album::class)->create();

        $response = $this->deleteAlbum($album->slug);

        $this->assertSame(0, Album::count());
        $response->assertStatus(204);
    }

    private function deleteAlbum(string $slug): TestResponse
    {
        return $this->json('delete', '/api/admin/albums/'.$slug);
    }

    public function test_user_can_not_destroy_a_album()
    {
        $this->actingAsUser();
        /** @var Album $album */
        $album = factory(Album::class)->create();

        $response = $this->deleteAlbum($album->slug);

        $this->assertSame(1, Album::count());
        $response->assertStatus(403);
    }

    public function test_guest_can_not_destroy_a_album_and_is_redirected_to_login()
    {
        /** @var Album $album */
        $album = factory(Album::class)->state('withUser')->create();

        $response = $this->deleteAlbum($album->slug);

        $this->assertSame(1, Album::count());
        $response->assertStatus(401);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
