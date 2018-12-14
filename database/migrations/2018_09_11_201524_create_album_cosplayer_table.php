<?php

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
            $table->integer('cosplayer_id')->unsigned()->nullable();
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
