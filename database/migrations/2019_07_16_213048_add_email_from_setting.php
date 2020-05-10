<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailFromSetting extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', static function (Blueprint $table): void {
            DB::table('settings')->insert([
                'name' => 'email_from',
                'value' => null,
                'type' => 'email',
                'title' => 'Email address to send notifications',
                'description' => '',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('name', 'email_from')->delete();
    }
}
