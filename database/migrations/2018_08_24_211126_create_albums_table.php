<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            //$table->string('slug')->unique();
            $table->string('seo_title')->nullable();
            //  $table->text('excerpt');
            $table->text('body')->nullable();;
            //  $table->text('meta_description');
            //  $table->text('meta_keywords');
            $table->boolean('active')->default(false);
            $table->integer('user_id')->unsigned();
            $table->string('image')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums');
    }
}
