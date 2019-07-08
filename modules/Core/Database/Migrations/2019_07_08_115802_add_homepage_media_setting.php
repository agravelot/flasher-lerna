<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHomepageMediaSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('settings')->insert([
            'name' => 'profile_picture_homepage',
            'value' => null,
            'type' => 'media',
            'title' => 'Homepage profile picture',
            'description' => '',
        ]);

        DB::table('settings')->insert([
            'name' => 'background_picture_homepage',
            'value' => null,
            'type' => 'media',
            'title' => 'Homepage background picture',
            'description' => '',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')->where('name', 'profile_picture_homepage')->delete();
        DB::table('settings')->where('name', 'background_picture_homepage')->delete();
    }
}
