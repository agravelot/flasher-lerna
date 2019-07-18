<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App;

use Illuminate\Support\Collection;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\MediaStream as MediaStreamBase;

class MediaStream extends MediaStreamBase
{
    protected function getFileNameWithSuffix(Collection $mediaItems, int $currentIndex): string
    {
        $nameCount = 0;

        /** @var Media $currentMedia */
        $currentMedia = $mediaItems[$currentIndex];

        foreach ($mediaItems as $index => $media) {
            if ($index >= $currentIndex) {
                break;
            }

            if ($currentMedia->name === $media->name) {
                $nameCount++;
            }
        }

        $extension = pathinfo($currentMedia->file_name, PATHINFO_EXTENSION);

        if ($nameCount === 0) {
            return "{$currentMedia->name}.{$extension}";
        }

        return "{$currentMedia->name} ({$nameCount}).{$extension}";
    }
}
