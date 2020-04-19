<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaAddedRequest;
use App\Http\Resources\UploadMediaCompletedResource;
use App\Models\Album;
use Illuminate\Http\JsonResponse;

class AdminMediaWebhook extends Controller
{
    public function store(MediaAddedRequest $request): JsonResponse
    {
        $album = Album::find($request->json('Upload.MetaData.modelId'));

        $remote = $request->json('HTTPRequest.RemoteAddr').$request->json('HTTPRequest.URI');

        $media = $album->addMediaFromUrl($remote)
            ->usingFileName($request->json('Upload.MetaData.filename'))
            ->preservingOriginal()
            ->toMediaCollectionOnCloudDisk(Album::PICTURES_COLLECTION);

        return (new UploadMediaCompletedResource($media))
            ->response()->setStatusCode(201);
    }
}
