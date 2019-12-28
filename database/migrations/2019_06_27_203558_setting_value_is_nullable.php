<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SettingValueIsNullable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', static function (Blueprint $table) {
            $table->string('value')->nullable()->change();
        });
        DB::table('settings')->where('value', '')->update(['value' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('value', null)->update(['value' => '']);
        Schema::table('settings', static function (Blueprint $table) {
            $table->string('value')->nullable(false)->change();
        });
    }
}
