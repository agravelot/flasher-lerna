<?php


namespace App;


use Spatie\MediaLibrary\ResponsiveImages\RegisteredResponsiveImages as RegisteredResponsiveImagesBase;
use Spatie\MediaLibrary\ResponsiveImages\ResponsiveImage;

class RegisteredResponsiveImages extends RegisteredResponsiveImagesBase {

    public function getSrcset(): string
    {
        $url = 'https://d11vat99ofzpe5.cloudfront.net/';

        $filesSrcset = $this->files
            ->map(function (ResponsiveImage $responsiveImage) use ($url) {

                $data = [
                    'bucket' => 'assets.jkanda.fr',
                    'key' => 'local/'.$responsiveImage->media->getPath(),
                    'edits' => [
                        'normalize' => true,
                        'sharpen' => true,
                        'resize' => [
                            'width' => $responsiveImage->width(),
                        ],
                    ],
                ];
                $encodedData = base64_encode(json_encode($data));

                \Log::debug($encodedData);
                \Log::debug(base64_decode($encodedData));

                return "${url}${encodedData} {$responsiveImage->width()}w";
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
