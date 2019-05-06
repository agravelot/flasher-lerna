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

class AddGoldenBookPostPublishedAtIndex extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('golden_book_posts', function (Blueprint $table) {
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('golden_book_posts', function (Blueprint $table) {
            $table->dropIndex('golden_book_posts_published_at_index');
        });
    }
}
