<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Dashboard\Http\Controllers;

use App\Models\User;
use App\Models\Contact;
use App\Models\Cosplayer;
use Illuminate\Http\JsonResponse;
use Modules\Album\Entities\Album;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Spatie\MediaLibrary\Models\Media;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function __invoke()
    {
        return response()->json([
            'user' => Auth::user()->name,
            'cosplayersCount' => Cosplayer::count(),
            'usersCount' => User::count(),
            'albumsCount' => Album::count(),
            'contactsCount' => Contact::count(),
            'albumMediasCount' => Media::count(),
            'activities' => Activity::with(['causer', 'subject'])->latest()->limit(10)->get(),
        ]);
    }
}
