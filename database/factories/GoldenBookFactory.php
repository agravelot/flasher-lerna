<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(\App\Models\GoldenBookPost::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'content' => $faker->paragraph,
        'active' => true,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
