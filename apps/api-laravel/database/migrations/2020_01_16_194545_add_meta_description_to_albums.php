<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddMetaDescriptionToAlbums extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('albums', static function (Blueprint $table): void {
            $table->string('meta_description', 255)->nullable();
        });

        DB::table('albums')->orderBy('id')->chunk(10, static function (Collection $albums): void {
            $albums->each(static function ($album): void {
                DB::table('albums')
                   ->where('id', $album->id)
                   ->update([
                       'meta_description' => Str::limit(strip_tags($album->body), 240),
                   ]);
            });
        });

        Schema::table('albums', static function (Blueprint $table): void {
            $table->string('meta_description', 255)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('albums', static function (Blueprint $table): void {
            $table->dropColumn('meta_description');
        });
    }
}
