<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddHomepageHeaderSubtitleSetting extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insert([
            'name' => 'homepage_header_subtitle',
            'value' => 'Some subtitle',
            'title' => 'Homepage header description',
            'description' => 'Enter a inspiring sentence about you',
            'type' => 'string',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('name', 'homepage_header_subtitle')->delete();
    }
}
