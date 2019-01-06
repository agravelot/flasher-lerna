<?php

namespace App\Observers;

use App\Models\Album;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AlbumObserver
{
    public function creating(Album $album)
    {
        if (!auth()->check()) {
            throw new UnauthorizedHttpException('You are not authenticated');
        }
        $album->user_id = auth()->id();
    }
}
