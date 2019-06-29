<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingValueIsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('value')->nullable()->change();
        });
        DB::table('settings')->where('value', '')->update(['value' => null]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')->where('value', null)->update(['value' => '']);
        Schema::table('settings', function (Blueprint $table) {
            $table->string('value')->nullable(false)->change();
        });
    }
}
