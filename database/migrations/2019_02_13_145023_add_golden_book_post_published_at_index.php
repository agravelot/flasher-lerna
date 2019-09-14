<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoldenBookPostPublishedAtIndex extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('golden_book_posts', static function (Blueprint $table) {
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('golden_book_posts', static function (Blueprint $table) {
            $table->dropIndex('golden_book_posts_published_at_index');
        });
    }
}
