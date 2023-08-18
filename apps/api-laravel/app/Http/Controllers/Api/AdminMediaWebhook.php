<?php

declare(strict_types=1);

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
        if ($request->headers->get('hook-name') !== 'post-finish') {
            return response()->json(['message' => 'Waiting post-finish hook.'], 200);
        }

        $album = Album::find($request->json('Upload.MetaData.modelId'));

        $remote = config('app.tusd_endpoint').$request->json('HTTPRequest.URI');

        [$width, $height] = getimagesize($remote->getRealPath());

        $media = $album->addMediaFromUrl($remote)
            ->usingFileName($request->json('Upload.MetaData.filename'))
            ->preservingOriginal()
            ->withCustomProperties(['width' => $width, 'height' => $height])
            ->toMediaCollectionOnCloudDisk(Album::PICTURES_COLLECTION);

        return (new UploadMediaCompletedResource($media))
            ->response()->setStatusCode(201);
    }
}
