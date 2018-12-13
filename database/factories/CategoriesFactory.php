<?php

use App\Models\Category;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->paragraph,
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});
