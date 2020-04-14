<?php

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
            fn (Media $media) => $media->update(['conversions_disk' => $media->disk])
        );
    }
}
