<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Cosplayer\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CosplayerRequest;
use App\Models\Cosplayer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Modules\Cosplayer\Transformers\CosplayerResource;

class AdminCosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return CosplayerResource::collection(Cosplayer::paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CosplayerRequest  $request
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
     * @param  Request  $request
     * @param  Cosplayer  $cosplayer
     *
     * @return CosplayerResource
     */
    public function update(CosplayerRequest $request, Cosplayer $cosplayer)
    {
        $cosplayer->update($request->all());

        if ($request->has('avatar')) {
            $cosplayer->avatar = $request->file('avatar');
        }

        return new CosplayerResource($cosplayer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     * @throws \Exception
     */
    public function destroy(Cosplayer $cosplayer)
    {
        $cosplayer->delete();

        return response()->json(null, 204);
    }
}
