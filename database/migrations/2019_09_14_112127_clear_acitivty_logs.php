<?php

use Illuminate\Database\Migrations\Migration;

class ClearAcitivtyLogs extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('activity_log')->truncate();
    }
}
