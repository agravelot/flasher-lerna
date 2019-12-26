<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

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
