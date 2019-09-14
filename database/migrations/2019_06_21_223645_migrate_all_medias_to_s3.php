<?php

use Illuminate\Database\Migrations\Migration;

class MigrateAllMediasToS3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('media')->update(['disk' => 's3']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('media')->update(['disk' => 'public']);
    }
}
