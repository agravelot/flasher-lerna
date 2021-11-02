<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixAlbumCategoryFK extends Migration
{
    public function up(): void
    {
        Schema::table('album_category', static function (Blueprint $table): void {
            $table->dropForeign(['album_id']);
            $table->dropForeign(['category_id']);
        });

        Schema::table('album_category', static function (Blueprint $table): void {
            $table->foreign('album_id')->references('id')->on('albums')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('album_category', static function (Blueprint $table): void {
            $table->dropForeign(['album_id']);
            $table->dropForeign(['category_id']);
        });

        Schema::table('album_category', static function (Blueprint $table): void {
            $table->foreign('album_id')->references('id')->on('albums')
                ->onDelete('set null')
                ->onUpdate('set null');
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('set null')
                ->onUpdate('set null');
        });
    }
}
