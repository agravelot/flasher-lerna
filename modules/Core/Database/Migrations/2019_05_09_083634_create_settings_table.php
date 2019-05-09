<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('settings');
        Schema::create('settings', function (Blueprint $table) {
            $table->char('name', 100)->primary();
            $table->string('value');
            $table->enum('type', ['string', 'numeric', 'bool', 'json']);
            $table->string('title');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        DB::table('settings')->insert([
           'name' => 'app_name',
           'value' => 'Flasher',
           'type' => 'string',
           'title' => 'Name of your website',
           'description' => 'Name of your website',
        ]);

        DB::table('settings')->insert([
            'name' => 'seo_description',
            'value' => '',
            'type' => 'string',
            'title' => 'SEO description',
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
        Schema::dropIfExists('settings');
    }
}
