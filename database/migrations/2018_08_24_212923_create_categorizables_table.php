<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorizablesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('categorizables', function (Blueprint $table) {
//            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->integer('categorizable_id')->unsigned();
            $table->string('categorizable_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('categorizables');
    }
}
