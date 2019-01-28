<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PictureAlbumRequest;
use App\Models\Album;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Spatie\MediaLibrary\Models\Media;

class AdminPictureAlbumController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     *
     * @return string
     */
    public function store(PictureAlbumRequest $request, FileReceiver $receiver)
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
            /** @var AbstractHandler $handler */
            $handler = $save->handler();

            return response()->json([
                'done' => $handler->getPercentageDone(),
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
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $slug)
    {
        $this->authorize('delete', Album::class);
        $album = Album::whereSlug($slug)
            ->firstOrFail();
        $this->authorize('delete', $album);
        $album->delete();

        return redirect(route('admin.albums.index'))
            ->withSuccess('Album successfully deleted');
    }
}
