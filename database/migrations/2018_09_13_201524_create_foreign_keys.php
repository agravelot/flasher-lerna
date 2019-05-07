<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('cosplayers', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('golden_book_posts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('album_cosplayer', function (Blueprint $table) {
            $table->foreign('album_id')->references('id')->on('albums')
                ->onDelete('set null')
                ->onUpdate('set null');
        });

        Schema::table('album_cosplayer', function (Blueprint $table) {
            $table->foreign('cosplayer_id')->references('id')->on('cosplayers')
                ->onDelete('set null')
                ->onUpdate('set null');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('set null');
        });

        Schema::table('categorizables', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->dropForeign('albums_user_id_foreign');
        });

        Schema::table('cosplayers', function (Blueprint $table) {
            $table->dropForeign('cosplayers_user_id_foreign');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('posts_user_id_foreign');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_user_id_foreign');
        });

        Schema::table('golden_book_posts', function (Blueprint $table) {
            $table->dropForeign('golden_book_posts_user_id_foreign');
        });

        Schema::table('album_cosplayer', function (Blueprint $table) {
            $table->dropForeign('album_cosplayer_album_id_foreign');
        });

        Schema::table('album_cosplayer', function (Blueprint $table) {
            $table->dropForeign('album_cosplayer_cosplayer_id_foreign');
        });

        Schema::table('categorizables', function (Blueprint $table) {
            $table->dropForeign('categorizables_category_id_foreign');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign('contacts_user_id_foreign');
        });
    }
}
