<?php

namespace Tests\Feature\Http\Controller\Api;

use App\Models\Album;
use App\Models\Contact;
use App\Models\Cosplayer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_return_valid_dashboard(): void
    {
        $this->actingAsAdmin();

        $response = $this->json('get', '/api/admin/dashboard');

        $response->assertJson([
            'user' => Auth::user()->token()->preferred_username,
            'cosplayersCount' => Cosplayer::count(),
            'usersCount' => User::count(),
            'albumsCount' => Album::count(),
            'contactsCount' => Contact::count(),
        ]);
    }
}
