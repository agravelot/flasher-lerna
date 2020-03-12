<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumMediaAddedRequest;
use App\Models\Album;
use Illuminate\Http\JsonResponse;

class AdminAlbumMediaWebhook extends Controller
{
    public function store(AlbumMediaAddedRequest $request): JsonResponse
    {
        $album = Album::findBySlug($request->get('album_slug'));

        return new JsonResponse(null, 204);
    }
}
