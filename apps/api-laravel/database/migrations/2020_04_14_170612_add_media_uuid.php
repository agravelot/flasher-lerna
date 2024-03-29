<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AddMediaUuid extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('media', static function (Blueprint $table): void {
            $table->uuid('uuid')->nullable();
            $table->string('conversions_disk')->nullable();
        });
        Media::cursor()->each(
            static fn (Media $media) => $media->update(['uuid' => Str::uuid()])
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
}
