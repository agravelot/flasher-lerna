<?php

namespace App;

use Dreamonkey\CloudFrontUrlSigner\Facades\CloudFrontUrlSigner;
use Spatie\MediaLibrary\ResponsiveImages\RegisteredResponsiveImages as RegisteredResponsiveImagesBase;
use Spatie\MediaLibrary\ResponsiveImages\ResponsiveImage;

class RegisteredResponsiveImages extends RegisteredResponsiveImagesBase
{
    public function getSrcset(): string
    {
        $url = config('medialibrary.s3.domain');

        $filesSrcset = $this->files
            ->map(function (ResponsiveImage $responsiveImage) use ($url) {
                $path = $this->media->getPath();
                if ($root = config('filesystems.disks.'.$this->media->disk.'.root')) {
                    $path = $root.'/'.$path;
                }

                $encodedData = base64_encode(json_encode(
                    [
                        'bucket' => 'assets.jkanda.fr',
                        'key' => $path,
                        'edits' => [
                            'normalize' => true,
                            'sharpen' => true,
                            'resize' => [
                                'width' => $responsiveImage->width(),
                            ],
                        ],
                    ]));

                $url = "${url}/${encodedData}";

                if (config('app.signed_medias_urls', false)) {
                    $url = CloudFrontUrlSigner::sign($url, 30);
                }

                return "${url} {$responsiveImage->width()}w";
            })
            ->implode(', ');

        $shouldAddPlaceholderSvg = config('medialibrary.responsive_images.use_tiny_placeholders')
            && $this->getPlaceholderSvg();

        if ($shouldAddPlaceholderSvg) {
            $filesSrcset .= ', '.$this->getPlaceholderSvg().' 32w';
        }

        return $filesSrcset;
    }
}
