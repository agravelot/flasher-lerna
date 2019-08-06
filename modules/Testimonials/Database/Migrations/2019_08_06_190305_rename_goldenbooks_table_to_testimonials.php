<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class RenameGoldenbooksTableToTestimonials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::rename('golden_book_posts', 'testimonials');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::rename('testimonials', 'golden_book_posts');
    }
}
