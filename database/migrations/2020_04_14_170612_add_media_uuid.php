<?php

use App\Models\Media;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class AddMediaUuid extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('media', static function (Blueprint $table) {
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
