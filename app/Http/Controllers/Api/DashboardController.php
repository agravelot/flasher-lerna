<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Album;
use App\Models\Contact;
use App\Models\Cosplayer;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'user' => Auth::user()->name,
            'cosplayersCount' => Cosplayer::count(),
            'usersCount' => User::count(),
            'albumsCount' => Album::count(),
            'contactsCount' => Contact::count(),
            'albumMediasCount' => Media::count(),
            'activities' => Activity::with(['causer', 'subject'])
                ->latest()->limit(10)->get(),
        ]);
    }
}
