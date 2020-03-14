<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumMediaAddedRequest;
use App\Http\Resources\UploadMediaCompletedResource;
use App\Models\Album;
use Illuminate\Http\JsonResponse;

class AdminAlbumMediaWebhook extends Controller
{
    public function store(AlbumMediaAddedRequest $request): JsonResponse
    {
        $album = Album::find($request->get('album_id'));
        $filePath = "albums/$album->id/{$request->get('media_name')}";

        $media = $album->addMediaFromDisk($filePath, 's3')
            ->usingFileName($request->get('media_name'))
            ->preservingOriginal()
            ->toMediaCollectionOnCloudDisk(Album::PICTURES_COLLECTION);

        return (new UploadMediaCompletedResource($media))
            ->response()->setStatusCode(201);
    }
}
