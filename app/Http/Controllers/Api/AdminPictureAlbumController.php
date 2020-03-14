<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeletePictureAlbumRequest;
use App\Http\Requests\StorePictureAlbumRequest;
use App\Http\Resources\AlbumShowResource;
use App\Http\Resources\UploadMediaCompletedResource;
use App\Http\Resources\UploadMediaProcessingResource;
use App\Jobs\DeleteAlbumMedia;
use App\Models\Album;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\Resource;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class AdminPictureAlbumController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Album::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse|UploadMediaProcessingResource
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
            return new UploadMediaProcessingResource($save);
        }

        $media = $album->addPicture($save->getFile());

        return (new UploadMediaCompletedResource($media))
            ->response()->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album, DeletePictureAlbumRequest $request): JsonResponse
    {
        Resource::withoutWrapping();

        DeleteAlbumMedia::dispatch($album->media->firstWhere('id', $request->get('media_id')));

        return (new AlbumShowResource($album))->response()->setStatusCode(204);
    }
}
