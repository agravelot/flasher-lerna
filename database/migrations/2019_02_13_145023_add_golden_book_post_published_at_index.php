<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoldenBookPostPublishedAtIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('golden_book_posts', function(Blueprint $table)
        {
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('golden_book_posts', function(Blueprint $table)
        {
            $table->dropIndex('golden_book_posts_published_at_index');
        });
    }
}
