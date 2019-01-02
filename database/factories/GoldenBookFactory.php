<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(\App\Models\GoldenBookPost::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'email' => $faker->email,
        'body' => $faker->paragraph,
        'active' => $faker->boolean,
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->state(\App\Models\GoldenBookPost::class, 'active', function () {
    return [
        'active' => true,
    ];
});

$factory->state(\App\Models\GoldenBookPost::class, 'unactive', function () {
    return [
        'active' => false,
    ];
});
