<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvitationToken extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invitations', static function (Blueprint $table): void {
            $table->string('token');
            $table->dateTime('confirmed_at')->nullable();
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', static function (Blueprint $table): void {
            $table->dropColumn('token');
            $table->dropColumn('confirmed_at');
            $table->dropIndex('email');
        });
    }
}
