<?php

declare(strict_types=1);

use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(\App\Models\Post::class, static function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'active' => true,
        'user_id' => 1,
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});
