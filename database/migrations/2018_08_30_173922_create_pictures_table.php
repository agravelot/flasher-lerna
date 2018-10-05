<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pictures', function (Blueprint $table) {
            $table->increments('id');
            //TODO Must be unique
            $table->string('filePath');
            $table->string('mineType');
            $table->string('originalName');
            //TODO Must be unique
            $table->string('hashName');
            $table->string('size');
            $table->string('extension');
            $table->integer('album_id')->unsigned()->nullable();
            $table->integer('album_header_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('album_id')->references('id')->on('albums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pictures');
    }
}
