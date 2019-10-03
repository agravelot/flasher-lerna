<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMediaTableToSupportBigUnsignedIntegerAsSize extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('media', static function (Blueprint $table) {
            $table->unsignedBigInteger('size')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', static function (Blueprint $table) {
            $table->unsignedInteger('size')->change();
        });
    }
}
