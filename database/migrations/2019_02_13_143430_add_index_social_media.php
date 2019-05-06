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

class AddIndexSocialMedia extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('social_media', function (Blueprint $table) {
            $table->index('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('social_media', function (Blueprint $table) {
            $table->dropIndex('social_media_active_index');
        });
    }
}
