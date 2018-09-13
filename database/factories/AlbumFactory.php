<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Album::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph($faker->randomDigit),
        'active' => $faker->boolean,
        'password' => null,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
