<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvitationToken extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invitations', static function (Blueprint $table) {
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
        Schema::table('invitations', static function (Blueprint $table) {
            $table->dropColumn('token');
            $table->dropColumn('confirmed_at');
            $table->dropIndex('email');
        });
    }
}
