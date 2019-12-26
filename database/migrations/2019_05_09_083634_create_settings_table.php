<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('settings');
        Schema::create('settings', static function (Blueprint $table) {
            $table->string('name', 30)->primary();
            $table->string('value');
            $table->enum('type', ['string', 'numeric', 'bool', 'json', 'textarea']);
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

        DB::table('settings')->insert([
            'name' => 'footer_content',
            'value' => '',
            'type' => 'textarea',
            'title' => 'Footer text content',
            'description' => '',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
}
