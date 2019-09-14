<?php

use Carbon\Carbon;
use App\Models\Testimonial;
use Faker\Generator as Faker;

$factory->define(Testimonial::class, static function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'email' => $faker->email,
        'body' => $faker->paragraph,
        'published_at' => Carbon::now(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->state(Testimonial::class, 'published', static function (Faker $faker) {
    return [
        'published_at' => Carbon::now(),
    ];
});

$factory->state(Testimonial::class, 'unpublished', static function () {
    return [
        'published_at' => null,
    ];
});
