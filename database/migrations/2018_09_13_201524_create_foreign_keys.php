<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('albums', static function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('cosplayers', static function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('posts', static function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('golden_book_posts', static function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('comments', static function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('album_cosplayer', static function (Blueprint $table): void {
            $table->foreign('album_id')->references('id')->on('albums')
                ->onDelete('set null')
                ->onUpdate('set null');
        });

        Schema::table('album_cosplayer', static function (Blueprint $table): void {
            $table->foreign('cosplayer_id')->references('id')->on('cosplayers')
                ->onDelete('set null')
                ->onUpdate('set null');
        });

        Schema::table('contacts', static function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('set null');
        });

        Schema::table('categorizables', static function (Blueprint $table): void {
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('albums', static function (Blueprint $table): void {
            $table->dropForeign('albums_user_id_foreign');
        });

        Schema::table('cosplayers', static function (Blueprint $table): void {
            $table->dropForeign('cosplayers_user_id_foreign');
        });

        Schema::table('posts', static function (Blueprint $table): void {
            $table->dropForeign('posts_user_id_foreign');
        });

        Schema::table('comments', static function (Blueprint $table): void {
            $table->dropForeign('comments_user_id_foreign');
        });

        Schema::table('golden_book_posts', static function (Blueprint $table): void {
            $table->dropForeign('golden_book_posts_user_id_foreign');
        });

        Schema::table('album_cosplayer', static function (Blueprint $table): void {
            $table->dropForeign('album_cosplayer_album_id_foreign');
        });

        Schema::table('album_cosplayer', static function (Blueprint $table): void {
            $table->dropForeign('album_cosplayer_cosplayer_id_foreign');
        });

        Schema::table('categorizables', static function (Blueprint $table): void {
            $table->dropForeign('categorizables_category_id_foreign');
        });

        Schema::table('contacts', static function (Blueprint $table): void {
            $table->dropForeign('contacts_user_id_foreign');
        });
    }
}
