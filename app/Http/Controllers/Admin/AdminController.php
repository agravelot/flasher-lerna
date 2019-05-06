<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Album;
use App\Models\Contact;
use App\Models\Cosplayer;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Auth\Access\AuthorizationException;

class AdminController extends Controller
{
    /**
     * Display dashboard.
     *
     * @throws AuthorizationException
     *
     * @return Response
     */
    public function __invoke()
    {
        $this->authorize('dashboard');

        return view('admin.dashboard.dashboard', [
            'userCount' => User::count(),
            'albumCount' => Album::count(),
            'cosplayerCount' => Cosplayer::count(),
            'contactCount' => Contact::count(),
            'activities' => Activity::with(['causer', 'subject'])->latest()->limit(10)->get(),
        ]);
    }
}
