<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Cosplayer\Http\Controllers;

use Exception;
use App\Models\Cosplayer;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Cosplayer\Http\Requests\CosplayerRequest;
use Modules\Cosplayer\Transformers\CosplayerResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminCosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return CosplayerResource::collection(
            QueryBuilder::for(Cosplayer::class)->allowedFilters('name')
                ->with('media')
                ->withCount('media')
                ->paginate(15)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CosplayerRequest $request): CosplayerResource
    {
        $cosplayer = Cosplayer::create($request->validated());

        if ($request->has('avatar')) {
            $cosplayer->avatar = $request->file('avatar');
        }

        return new CosplayerResource($cosplayer);
    }

    /**
     * Show the specified resource.
     */
    public function show(Cosplayer $cosplayer): CosplayerResource
    {
        $cosplayer->load('user');

        return new CosplayerResource($cosplayer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CosplayerRequest $request, Cosplayer $cosplayer): CosplayerResource
    {
        $cosplayer->slug = null;
        $cosplayer->update($request->validated());

        if ($request->has('avatar')) {
            $cosplayer->avatar = $request->file('avatar');
        }

        return new CosplayerResource($cosplayer);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(Cosplayer $cosplayer): JsonResponse
    {
        $cosplayer->delete();

        return response()->json(null, 204);
    }
}
