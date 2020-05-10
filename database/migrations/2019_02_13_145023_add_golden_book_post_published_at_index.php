<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoldenBookPostPublishedAtIndex extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('golden_book_posts', static function (Blueprint $table): void {
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('golden_book_posts', static function (Blueprint $table): void {
            $table->dropIndex('golden_book_posts_published_at_index');
        });
    }
}
