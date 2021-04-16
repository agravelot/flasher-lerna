<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SettingsMoveToAutoIncrementPK extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', static function (Blueprint $table): void {
            $table->dropPrimary();
        });
        Schema::table('settings', static function (Blueprint $table): void {
            $table->bigIncrements('id')->first();
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', static function (Blueprint $table): void {
            $table->dropIndex(['name']);
        });

        Schema::table('settings', static function (Blueprint $table): void {
            $table->dropColumn('id');
            $table->primary('name');
        });
    }
}
