<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\SocialMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\SocialMediaRequest;
use App\Http\Resources\SocialMediaResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminSocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return SocialMediaResource::collection(
            QueryBuilder::for(SocialMedia::class)
                ->allowedFilters('name')->paginate()
        );
    }

    /**
     * Show the specified resource.
     */
    public function show(SocialMedia $socialMedia): SocialMediaResource
    {
        return new SocialMediaResource($socialMedia);
    }

    public function store(SocialMediaRequest $request): SocialMediaResource
    {
        $socialMedia = SocialMedia::create($request->validated());

        return new SocialMediaResource($socialMedia);
    }

    public function update(SocialMediaRequest $request, SocialMedia $socialMedia): SocialMediaResource
    {
        $socialMedia->update($request->validated());

        return new SocialMediaResource($socialMedia);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws Exception
     */
    public function destroy(SocialMedia $socialMedia): JsonResponse
    {
        $socialMedia->delete();

        return response()->json(null, 204);
    }
}
