<?php

namespace App;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\ResponsiveImages\ResponsiveImage;
use Spatie\MediaLibrary\ResponsiveImages\ResponsiveImageGenerator as ResponsiveImageGeneratorBase;
use Spatie\TemporaryDirectory\TemporaryDirectory as BaseTemporaryDirectory;

class ResponsiveImageGenerator extends ResponsiveImageGeneratorBase
{
    public function generateResponsiveImage(
        Media $media,
        string $baseImage,
        string $conversionName,
        int $targetWidth,
        BaseTemporaryDirectory $temporaryDirectory
    ) {
        $responsiveImagePath = $this->appendToFileName($media->file_name, "___{$conversionName}_{$targetWidth}");
        $finalImageFileName = $this->appendToFileName($responsiveImagePath, '_NONE');

        ResponsiveImage::register($media, $finalImageFileName, $conversionName);
    }
}
