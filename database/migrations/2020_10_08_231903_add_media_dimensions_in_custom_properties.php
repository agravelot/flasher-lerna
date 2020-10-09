<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class AddMediaDimensionsInCustomProperties extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $media */
        $class = config('media-library.media_model');

        $class::where('custom_properties->width', null)
            ->orWhere('custom_properties->height', null)
            ->orderBy('id')
            ->each(static function (Spatie\MediaLibrary\MediaCollections\Models\Media $media): void {
                Log::notice('Getting dimensions from : '.$media->getFullUrl());
                [$width, $height] = getimagesize($media->getFullUrl());
                $media->setCustomProperty('width', $width);
                $media->setCustomProperty('height', $height);
                $media->save();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // TODO
    }
}
