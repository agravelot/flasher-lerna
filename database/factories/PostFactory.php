<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(\App\Models\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'active' => true,
        'user_id' => 1,
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});
