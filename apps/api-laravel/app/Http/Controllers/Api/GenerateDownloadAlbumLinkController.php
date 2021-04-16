<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;

class GenerateDownloadAlbumLinkController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function __invoke(Album $album): JsonResponse
    {
        $this->authorize('generateDownload', $album);

        return response()->json([
            'url' => URL::temporarySignedRoute('api.download-albums.show', now()->addHours(1), ['album' => $album]),
        ]);
    }
}
