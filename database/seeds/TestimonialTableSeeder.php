<?php

use Illuminate\Database\Seeder;

class TestimonialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(\App\Models\Testimonial::class, 20)
            ->state('published')
            ->create();
        factory(\App\Models\Testimonial::class, 20)
            ->state('unpublished')
            ->create();
    }
}
