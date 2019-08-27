<?php

namespace Modules\SocialMedia\Http\Controllers;

use App\Http\Requests\SocialMediaRequest;
use App\Models\SocialMedia;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\SocialMedia\Transformers\SocialMediaResource;
use Spatie\QueryBuilder\QueryBuilder;

class AdminSocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
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
     *
     * @param  SocialMedia  $socialMedia
     *
     * @return SocialMediaResource
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
     * @param  SocialMedia  $socialMedia
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(SocialMedia $socialMedia): JsonResponse
    {
        $socialMedia->delete();

        return response()->json(null, 204);
    }
}
