<?php

namespace App\Generators;

use Spatie\MediaLibrary\Exceptions\UrlCannotBeDetermined;
use Spatie\MediaLibrary\UrlGenerator\LocalUrlGenerator;

class RelativeLocalUrlGenerator extends LocalUrlGenerator
{
    /**
     * Get the url to the directory containing responsive images.
     *
     * @throws UrlCannotBeDetermined
     */
    public function getResponsiveImagesDirectoryUrl(): string
    {
        return $this->getBaseMediaDirectoryUrl().'/'.$this->pathGenerator->getPathForResponsiveImages($this->media);
    }
}
