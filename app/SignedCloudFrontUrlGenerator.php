<?php

namespace App;

use Dreamonkey\CloudFrontUrlSigner\Facades\CloudFrontUrlSigner;
use Spatie\MediaLibrary\UrlGenerator\S3UrlGenerator;

class SignedCloudFrontUrlGenerator extends S3UrlGenerator
{
    public function getUrl(): string
    {
        return CloudFrontUrlSigner::sign(parent::getUrl(), 30);
    }

    public function getResponsiveImagesDirectoryUrl(): string
    {
        return CloudFrontUrlSigner::sign(parent::getResponsiveImagesDirectoryUrl(), 30);
    }
}
