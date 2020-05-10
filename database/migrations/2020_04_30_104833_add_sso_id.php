<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSsoId extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('albums', static function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(true)->change();
            $table->uuid('sso_id')->nullable();
        });

        Schema::table('cosplayers', static function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(true)->change();
            $table->uuid('sso_id')->nullable();
        });

        Schema::table('posts', static function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(true)->change();
            $table->uuid('sso_id');
        });

        Schema::table('testimonials', static function (Blueprint $table) {
            $table->dropForeign('golden_book_posts_user_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable(true)->change();
            $table->uuid('sso_id')->nullable();
        });

        Schema::table('contacts', static function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(true)->change();
            $table->uuid('sso_id')->nullable();
        });

        Schema::table('comments', static function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(true)->change();
            $table->uuid('sso_id');
        });

        Schema::rename('users', 'users_save');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('users_save', 'users');

        Schema::table('albums', static function (Blueprint $table) {
            $table->dropColumn('sso_id');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('cosplayers', static function (Blueprint $table) {
            $table->dropColumn('sso_id');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('posts', static function (Blueprint $table) {
            $table->dropColumn('sso_id');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('testimonials', static function (Blueprint $table) {
            $table->dropColumn('sso_id');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id', 'golden_book_posts_user_id_foreign')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('contacts', static function (Blueprint $table) {
            $table->dropColumn('sso_id');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('comments', static function (Blueprint $table) {
            $table->dropColumn('sso_id');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
}
