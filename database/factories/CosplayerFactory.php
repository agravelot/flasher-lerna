<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Cosplayer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});
