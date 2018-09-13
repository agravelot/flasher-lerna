<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
//        Schema::table('post_tag', function (Blueprint $table) {
//            $table->foreign('post_id')->references('id')->on('posts')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');
//        });
//        Schema::table('post_tag', function (Blueprint $table) {
//            $table->foreign('tag_id')->references('id')->on('tags')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');
//        });
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
//        Schema::table('comments', function (Blueprint $table) {
//            $table->foreign('post_id')->references('id')->on('posts')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');
//        });
        Schema::table('category_post', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('category_post', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts')
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('posts_user_id_foreign');
        });
//        Schema::table('post_tag', function (Blueprint $table) {
//            $table->dropForeign('post_tag_post_id_foreign');
//        });
//        Schema::table('post_tag', function (Blueprint $table) {
//            $table->dropForeign('post_tag_tag_id_foreign');
//        });
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_user_id_foreign');
        });
//        Schema::table('comments', function (Blueprint $table) {
//            $table->dropForeign('comments_post_id_foreign');
//        });
//        Schema::table('comments', function (Blueprint $table) {
//            $table->dropForeign('comments_parent_id_foreign');
//        });
        Schema::table('category_post', function (Blueprint $table) {
            $table->dropForeign('category_post_category_id_foreign');
        });
        Schema::table('category_post', function (Blueprint $table) {
            $table->dropForeign('category_post_post_id_foreign');
        });
        Schema::table('album_cosplayer', function (Blueprint $table) {
            $table->dropForeign('album_cosplayer_album_id_foreign');
        });
//        Schema::table('album_cosplayer', function (Blueprint $table) {
//            $table->dropForeign('album_id_cosplayer_id_foreign');
//        });
    }
}
