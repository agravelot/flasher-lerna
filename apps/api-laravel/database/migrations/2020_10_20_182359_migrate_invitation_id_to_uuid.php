<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MigrateInvitationIdToUuid extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invitations', static function (Blueprint $table): void {
            $table->dropPrimary('id');
            $table->renameColumn('id', 'old_id');
        });

        Schema::table('invitations', static function (Blueprint $table): void {
            $table->uuid('uuid')->nullable();
        });

        DB::table('invitations')->cursor()
            ->each(static fn ($invitation) => DB::table('invitations')->where([
                'old_id' => $invitation->old_id,
            ])->update(['uuid' => Str::uuid()]));

        Schema::table('invitations', static function (Blueprint $table): void {
            $table->uuid('uuid')->nullable(false)->change();
            $table->primary('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', static function (Blueprint $table): void {
            $table->dropPrimary(['id']);
            $table->dropColumn('id');
            $table->renameColumn('old_id', 'id');
            $table->unsignedBigInteger('id')->primary()->change();
        });
    }
}
