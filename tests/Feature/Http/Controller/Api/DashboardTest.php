<?php

namespace Tests\Feature\Http\Controller\Api;

use App\Models\Album;
use App\Models\Contact;
use App\Models\Cosplayer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function testExample()
    {
        $this->actingAsAdmin();

        $response = $this->json('get', '/api/admin/dashboard');

        $response->assertJson([
            'user' => Auth::user()->name,
            'cosplayersCount' => Cosplayer::count(),
            'usersCount' => User::count(),
            'albumsCount' => Album::count(),
            'contactsCount' => Contact::count(),
        ]);
    }
}
