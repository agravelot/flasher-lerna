<?php

namespace Tests\Feature\Http\Controller\Admin\Album;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_published_albums()
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('published')->create();

        $response = $this->get('/admin/albums');

        $response->assertStatus(200)
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }


    public function test_admin_can_view_unpublished_albums()
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('unpublished')->create();

        $response = $this->get('/admin/albums');

        $response->assertStatus(200)
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_admin_can_view_secured_albums()
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('password')->create();

        $response = $this->get('/admin/albums');

        $response->assertStatus(200)
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_admin_can_view_password_less_albums()
    {
        $this->actingAsAdmin();
        $albums = factory(Album::class, 5)->state('passwordLess')->create();

        $response = $this->get('/admin/albums');

        $response->assertStatus(200)
            ->assertSeeInOrder($albums->pluck('title')->toArray());
    }

    public function test_user_can_not_view_index()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/albums');

        $response->assertStatus(403);
    }

    public function test_guest_can_not_view_index()
    {
        $response = $this->get('/admin/albums');

        $response->assertRedirect('/login');
        $this->followRedirects($response)
            ->assertStatus(200);
    }
}