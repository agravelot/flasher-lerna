<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateToStringEnum extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', static function (Blueprint $table) {
            $table->string('type_new')->after('name')->nullable();
        });

        DB::table('settings')->where('type', 'string')->update(['type_new' => 'string']);
        DB::table('settings')->where('type', 'numeric')->update(['type_new' => 'numeric']);
        DB::table('settings')->where('type', 'bool')->update(['type_new' => 'bool']);
        DB::table('settings')->where('type', 'json')->update(['type_new' => 'json']);
        DB::table('settings')->where('type', 'textarea')->update(['type_new' => 'textarea']);

        Schema::table('settings', static function (Blueprint $table) {
            // Remove nullable
            $table->string('type_new')->nullable(false)->change();
            $table->dropColumn('type');
            $table->renameColumn('type_new', 'type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', static function (Blueprint $table) {
            //
        });
    }
}
