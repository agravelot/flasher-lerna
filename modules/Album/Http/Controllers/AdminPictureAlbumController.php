<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Album\Entities\Album;
use Modules\Album\Http\Requests\DeletePictureAlbumRequest;
use Modules\Album\Http\Requests\StorePictureAlbumRequest;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Spatie\MediaLibrary\Models\Media;

class AdminPictureAlbumController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StorePictureAlbumRequest $request
     * @param FileReceiver             $receiver
     *
     * @throws UploadMissingFileException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return string
     */
    public function store(StorePictureAlbumRequest $request, FileReceiver $receiver)
    {
        $this->authorize('create', Album::class);
        /** @var Album $album */
        $album = Album::whereSlug($request->validated()['album_slug'])->firstOrFail();

        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        $save = $receiver->receive();

        // check if the upload has not finished (in chunk mode it will send smaller files)
        if (! $save->isFinished()) {
            // we are in chunk mode, lets send the current progress
            return response()->json([
                'done' => $save->handler()->getPercentageDone(),
                'status' => true,
            ]);
        }

        /** @var Media $file */
        $file = $album->addMedia($save->getFile())
            ->preservingOriginal()
            ->withResponsiveImages()
            ->toMediaCollection('pictures');

        return response()->json([
            'path' => $file->getUrl(),
            'name' => $file->file_name,
            'mime_type' => $file->mime_type,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string                    $albumSlug
     * @param DeletePictureAlbumRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(string $albumSlug, DeletePictureAlbumRequest $request)
    {
        $this->authorize('delete', Album::class);
        $album = Album::whereSlug($albumSlug)->firstOrFail();
        $this->authorize('delete', $album);
        $deleted = optional($album->getMedia('pictures')->get($request->get('media_id')))->delete();

        if ($deleted === null || ! $deleted) {
            return response()->json(['error' => 'media not found'], 400);
        }

        return response()->json(null, 204);
    }
}
