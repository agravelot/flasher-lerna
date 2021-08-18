<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeletePictureAlbumRequest;
use App\Http\Requests\StorePictureAlbumRequest;
use App\Http\Resources\AlbumShowResource;
use App\Http\Resources\UploadMediaCompletedResource;
use App\Jobs\DeleteAlbumMedia;
use App\Models\Album;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminPictureAlbumController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Album::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePictureAlbumRequest $request): JsonResponse
    {
        set_time_limit(0);
        JsonResource::withoutWrapping();
        /** @var Album $album */
        $album = Album::whereSlug($request->get('album_slug'))->firstOrFail();

        $media = $album->addPicture($request->file('file'));

        return (new UploadMediaCompletedResource($media))
            ->response()->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album, DeletePictureAlbumRequest $request): JsonResponse
    {
        JsonResource::withoutWrapping();

        DeleteAlbumMedia::dispatch($album->media->firstWhere('id', $request->get('media_id')));

        return (new AlbumShowResource($album))->response()->setStatusCode(204);
    }
}
