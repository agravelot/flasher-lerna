<?php

namespace App;

use Dreamonkey\CloudFrontUrlSigner\Facades\CloudFrontUrlSigner;
use Spatie\MediaLibrary\UrlGenerator\S3UrlGenerator;

class SignedCloudFrontUrlGenerator extends S3UrlGenerator
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
