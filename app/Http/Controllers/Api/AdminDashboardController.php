<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Contact;
use App\Models\Cosplayer;
use App\Models\Media;
use App\Models\User;
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
            'user' => $request->user()->token->preferred_username,
            'cosplayersCount' => Cosplayer::count(),
            'albumsCount' => Album::count(),
            'contactsCount' => Contact::count(),
            'albumMediasCount' => Media::count(),
        ]);
    }
}
