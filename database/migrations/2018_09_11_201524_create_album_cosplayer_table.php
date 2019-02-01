<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumCosplayerTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('album_cosplayer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('album_id')->unsigned()->nullable();
            $table->unsignedInteger('cosplayer_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('album_cosplayer');
    }
}
