<?php

namespace Tests\Feature\Http\Controller\Front\Profile\MyAlbums;

use Tests\TestCase;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowMyAlbumsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_show_his_albums(): void
    {
        $this->disableExceptionHandling();
        $this->actingAsAdmin();

        $response = $this->getMyAlbums();

        $response->assertOk();
    }

    public function test_user_can_show_his_albums(): void
    {
        $this->actingAsUser();

        $response = $this->getMyAlbums();

        $response->assertOk();
    }

    public function test_guest_can_no_show_his_albums(): void
    {
        $response = $this->getMyAlbums();

        $response->assertRedirect('/login');
    }

    private function getMyAlbums(): TestResponse
    {
        return $this->get('/profile/my-albums');
    }
}
