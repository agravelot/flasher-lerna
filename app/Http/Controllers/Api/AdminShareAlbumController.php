<?php

namespace App\Http\Controllers\Api\Admin\AlbumShare;

use App\Http\Requests\ShareAlbumRequest;
use App\Models\Album;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class AdminShareAlbumController extends Controller
{
    /**
     * Share the album to the given contacts.
     * @throws AuthorizationException
     */
    public function __invoke(Album $album, ShareAlbumRequest $request): JsonResponse
    {
        $this->authorize('update', Album::class);

        return new JsonResponse(null, 201);
    }
}
