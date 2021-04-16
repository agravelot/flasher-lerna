<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SetMediaConversionsDisk extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Media::cursor()->each(
            static fn (Media $media) => $media->update(['conversions_disk' => $media->disk])
        );
    }
}
