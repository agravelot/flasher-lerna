<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Dashboard\Tests;

use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use App\Models\Cosplayer;
use Modules\Album\Entities\Album;

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
