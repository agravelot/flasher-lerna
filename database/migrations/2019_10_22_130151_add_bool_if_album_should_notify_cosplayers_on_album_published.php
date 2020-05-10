<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBoolIfAlbumShouldNotifyCosplayersOnAlbumPublished extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('albums', static function (Blueprint $table) {
            $table->boolean('notify_users_on_published')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('albums', static function (Blueprint $table) {
            $table->dropColumn('notify_users_on_published');
        });
    }
}
