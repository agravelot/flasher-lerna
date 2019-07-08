<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

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
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('type_new')->after('name')->nullable();
        });

        DB::table('settings')->where('type', 'string')->update(['type_new' => 'string']);
        DB::table('settings')->where('type', 'numeric')->update(['type_new' => 'numeric']);
        DB::table('settings')->where('type', 'bool')->update(['type_new' => 'bool']);
        DB::table('settings')->where('type', 'json')->update(['type_new' => 'json']);
        DB::table('settings')->where('type', 'textarea')->update(['type_new' => 'textarea']);

        Schema::table('settings', function (Blueprint $table) {
            // Remove nullable
            $table->string('type_new')->nullable(false)->change();
            $table->dropColumn('type');
            $table->renameColumn('type_new', 'type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
}
