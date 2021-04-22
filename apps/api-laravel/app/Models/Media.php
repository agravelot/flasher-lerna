<?php

declare(strict_types=1);

namespace App\Models;

use App\RegisteredResponsiveImages;
use Spatie\MediaLibrary\MediaCollections\Models\Media as MediaBase;
use Spatie\MediaLibrary\ResponsiveImages\RegisteredResponsiveImages as RegisteredResponsiveImagesBase;

class Media extends MediaBase
{
    public function responsiveImages(string $conversionName = ''): RegisteredResponsiveImagesBase
    {
        if (config('app.new_conversions', false)) {
            return new RegisteredResponsiveImages($this, $conversionName);
        }

        return parent::responsiveImages($conversionName);
    }
}
