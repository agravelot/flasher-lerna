<?php

namespace App\Http\Controllers\Api;

use App\Facades\Keycloak;
use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Contact;
use App\Models\Cosplayer;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()->name,
            'cosplayersCount' => Cosplayer::count(),
            'usersCount' => Keycloak::users()->count(),
            'albumsCount' => Album::count(),
            'contactsCount' => Contact::count(),
            'albumMediasCount' => Media::count(),
        ]);
    }
}
