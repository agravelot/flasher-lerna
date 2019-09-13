<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Api;

use App\Models\Album;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Transformers\AlbumShowResource;
use Illuminate\Http\Resources\Json\Resource;
use App\Http\Requests\StorePictureAlbumRequest;
use App\Http\Requests\DeletePictureAlbumRequest;
use App\Transformers\CompleteUploadPictureResource;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use App\Transformers\ProcessingUploadPictureResource;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

class AdminPictureAlbumController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Album::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse|ProcessingUploadPictureResource
     * @throws UploadMissingFileException
     */
    public function store(StorePictureAlbumRequest $request, FileReceiver $receiver)
    {
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        Resource::withoutWrapping();
        /** @var Album $album */
        $album = Album::whereSlug($request->get('album_slug'))->firstOrFail();
        $save = $receiver->receive();

        // check if the upload has not finished (in chunk mode it will send smaller files)
        if (! $save->isFinished()) {
            // we are in chunk mode, lets send the current progress
            return new ProcessingUploadPictureResource($save);
        }

        $media = $album->addPicture($save->getFile());

        return (new CompleteUploadPictureResource($media))
            ->response()->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album, DeletePictureAlbumRequest $request): JsonResponse
    {
        Resource::withoutWrapping();

        $album->media->firstWhere('id', $request->get('media_id'))->delete();

        return (new AlbumShowResource($album))->response()->setStatusCode(204);
    }
}
