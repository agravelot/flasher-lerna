<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumMediaOrderingRequest;
use App\Http\Resources\AlbumShowResource;
use App\Models\Album;
use App\Models\Media;
use Illuminate\Http\JsonResponse;

class AlbumMediaOrderingController extends Controller
{
    public function __invoke(Album $album, AlbumMediaOrderingRequest $request): AlbumShowResource
    {
        Media::setNewOrder($request->media_ids);

        return new AlbumShowResource($album);
    }
}
