<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use Illuminate\Database\Migrations\Migration;

class AddHomePageTitleSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE settings CHANGE type type ENUM('string', 'numeric', 'bool', 'json', 'textarea')");

        DB::table('settings')->insert([
            'name' => 'homepage_title',
            'value' => 'My albums',
            'type' => 'string',
            'title' => 'Homepage title',
            'description' => 'The title of the home page that will be displayed on search engines',
        ]);

        DB::table('settings')->insert([
            'name' => 'homepage_subtitle',
            'value' => 'Discover my albums',
            'type' => 'string',
            'title' => 'Homepage subtitle',
            'description' => 'Subtitle of the homepage',
        ]);

        DB::table('settings')->insert([
            'name' => 'homepage_description',
            'value' => '',
            'type' => 'textarea',
            'title' => 'Homepage description',
            'description' => 'Home page presentation text',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')->where('name', 'homepage_title')->delete();
        DB::table('settings')->where('name', 'homepage_subtitle')->delete();
        DB::table('settings')->where('name', 'homepage_description')->delete();
        DB::statement("ALTER TABLE settings CHANGE type type ENUM('string', 'numeric', 'bool', 'json')");
    }
}
