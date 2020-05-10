<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultPageTitleSetting extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', static function (Blueprint $table): void {
            DB::table('settings')->insert([
                'name' => 'default_page_title',
                'value' => 'Default page title',
                'type' => 'string',
                'title' => 'Default page title',
                'description' => '',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('name', 'default_page_title')->delete();
    }
}
