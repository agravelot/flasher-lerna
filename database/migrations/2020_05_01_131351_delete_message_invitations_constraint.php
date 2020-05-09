<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteMessageInvitationsConstraint extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invitations', static function (BluePrint $table) {
            $table->text('message')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', static function (BluePrint $table) {
            $table->text('message')->nullable(false)->change();
        });
    }
}
