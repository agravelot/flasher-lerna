<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\MediaStream as MediaStreamBase;

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
