<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Album::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph($faker->randomDigit),
        'published_at' => rand(0, 1) ? $faker->dateTime : null,
        'password' => rand(0, 1) ? 'secret' : null,
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});
