<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Modules\Album\Http\Requests\DeletePictureAlbumRequest;
use Modules\Album\Http\Requests\StorePictureAlbumRequest;
use Modules\Album\Transformers\AlbumIndexResource;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AdminPictureAlbumController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Album::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePictureAlbumRequest $request
     * @param FileReceiver             $receiver
     *
     * @throws UploadMissingFileException
     *
     * @return string
     */
    public function store(StorePictureAlbumRequest $request, FileReceiver $receiver)
    {
        /** @var Album $album */
        $album = Album::whereSlug($request->validated()['album_slug'])->firstOrFail();

        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        $save = $receiver->receive();

        // check if the upload has not finished (in chunk mode it will send smaller files)
        if (! $save->isFinished()) {
            // we are in chunk mode, lets send the current progress
            //TODO Use resource
            return response()->json([
                'done' => $save->handler()->getPercentageDone(),
                'status' => true,
            ]);
        }

        $media = $album->addPicture($save->getFile());

        //TODO User resource
        return response()->json([
            'path' => $media->getUrl(),
            'name' => $media->file_name,
            'mime_type' => $media->mime_type,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Album                     $album
     * @param DeletePictureAlbumRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Album $album, DeletePictureAlbumRequest $request)
    {
        //TODO Move in request
        if (! $album->media->pluck('id')->containsStrict($request->media_id)) {
            throw new UnprocessableEntityHttpException('media not found');
        }

        $album->media->firstWhere('id', $request->media_id)->delete();

        return (new AlbumIndexResource($album))->response()->setStatusCode(204);
    }
}
