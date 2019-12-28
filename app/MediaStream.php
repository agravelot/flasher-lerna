<?php

namespace App;

use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaStream as MediaStreamBase;
use Spatie\MediaLibrary\Models\Media;

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

        $extension = $currentMedia->getExtensionAttribute();

        if ($nameCount === 0) {
            return "{$currentMedia->name}.{$extension}";
        }

        return "{$currentMedia->name} ({$nameCount}).{$extension}";
    }
}
