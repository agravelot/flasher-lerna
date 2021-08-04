<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Facades\Keycloak;
use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Contact;
use App\Models\Cosplayer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'cosplayersCount' => Cosplayer::count(),
            'usersCount' => Keycloak::users()->count(),
            'albumsCount' => Album::count(),
            'contactsCount' => Contact::count(),
            'albumMediasCount' => Media::count(),
        ]);
    }
}
