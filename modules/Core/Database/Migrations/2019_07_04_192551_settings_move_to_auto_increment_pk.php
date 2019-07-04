<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingsMoveToAutoIncrementPK extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropPrimary();
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->bigIncrements('id')->first();
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->primary('name');

        });
    }
}
