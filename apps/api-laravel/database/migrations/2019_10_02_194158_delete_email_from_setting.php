<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

class DeleteEmailFromSetting extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->where('name', 'email_from')->delete();
    }
}
