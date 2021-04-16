<?php

declare(strict_types=1);

namespace App;

use Dreamonkey\CloudFrontUrlSigner\Facades\CloudFrontUrlSigner;
use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

class SignedCloudFrontUrlGenerator extends DefaultUrlGenerator
{
    public function getUrl(): string
    {
        if (! config('app.signed_medias_urls', false)) {
            return parent::getUrl();
        }

        return CloudFrontUrlSigner::sign(parent::getUrl(), 30);
    }

    public function getResponsiveImagesDirectoryUrl(): string
    {
        if (! config('app.signed_medias_urls', false)) {
            return parent::getResponsiveImagesDirectoryUrl();
        }

        return CloudFrontUrlSigner::sign(parent::getResponsiveImagesDirectoryUrl(), 30);
    }
}
