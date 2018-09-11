<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Album::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph(10),
        'active' => $faker->boolean,
        'password' => null,
        'user_id' => 1,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
