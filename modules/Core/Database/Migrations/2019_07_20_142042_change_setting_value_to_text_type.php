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

class ChangeSettingValueToTextType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('value')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('value')->change();
        });
    }
}
