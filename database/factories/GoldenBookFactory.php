<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(\App\Models\GoldenBookPost::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'email' => $faker->email,
        'body' => $faker->paragraph,
        'active' => true,
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});
