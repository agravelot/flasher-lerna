<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\UrlGenerator;

use Spatie\MediaLibrary\UrlGenerator\LocalUrlGenerator;

class RelativeLocalUrlGenerator extends LocalUrlGenerator
{
    /**
     * Get the url to the directory containing responsive images.
     *
     * @return string
     */
    public function getResponsiveImagesDirectoryUrl(): string
    {
        return $this->getBaseMediaDirectoryUrl().'/'.$this->pathGenerator->getPathForResponsiveImages($this->media);
    }
}
