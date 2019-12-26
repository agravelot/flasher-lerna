<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBoolIfAlbumShouldNotifyCosplayersOnAlbumPublished extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('albums', static function (Blueprint $table) {
            $table->boolean('notify_users_on_published')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('albums', static function (Blueprint $table) {
            $table->dropColumn('notify_users_on_published');
        });
    }
}
