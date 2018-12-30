<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('body')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->string('password')->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('albums');
    }
}
