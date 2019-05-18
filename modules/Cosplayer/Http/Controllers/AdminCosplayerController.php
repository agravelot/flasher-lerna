<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Cosplayer\Http\Controllers;

use App\Models\Cosplayer;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\CosplayerRequest;
use Modules\Cosplayer\Transformers\CosplayerResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminCosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
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
     *
     * @param CosplayerRequest $request
     *
     * @return CosplayerResource
     */
    public function store(CosplayerRequest $request)
    {
        $cosplayer = Cosplayer::create($request->validated());

        if ($request->has('avatar')) {
            $cosplayer->avatar = $request->file('avatar');
        }

        return new CosplayerResource($cosplayer);
    }

    /**
     * Show the specified resource.
     *
     * @return CosplayerResource
     */
    public function show(Cosplayer $cosplayer)
    {
        return new CosplayerResource($cosplayer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CosplayerRequest $request
     * @param Cosplayer        $cosplayer
     *
     * @return CosplayerResource
     */
    public function update(CosplayerRequest $request, Cosplayer $cosplayer)
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
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Cosplayer $cosplayer)
    {
        $cosplayer->delete();

        return response()->json(null, 204);
    }
}
