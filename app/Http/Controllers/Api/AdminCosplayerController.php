<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CosplayerRequest;
use App\Http\Resources\CosplayerResource;
use App\Models\Cosplayer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

class AdminCosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return CosplayerResource::collection(
            QueryBuilder::for(Cosplayer::class)
                ->allowedFilters('name')
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
