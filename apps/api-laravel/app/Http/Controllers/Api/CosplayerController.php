<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CosplayerResource;
use App\Models\Cosplayer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return CosplayerResource::collection(Cosplayer::paginate($request->query->getInt('per_page', 15)));
    }

    /**
     * Show the specified resource.
     */
    public function show(Cosplayer $cosplayer): CosplayerResource
    {
        return new CosplayerResource($cosplayer);
    }
}
