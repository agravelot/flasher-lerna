<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class RenameGoldenbooksTableToTestimonials extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('golden_book_posts', 'testimonials');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('testimonials', 'golden_book_posts');
    }
}
