<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use App\Models\GoldenBookPost;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(GoldenBookPost::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'email' => $faker->email,
        'body' => $faker->paragraph,
        'published_at' => Carbon::now(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->state(GoldenBookPost::class, 'published', function (Faker $faker) {
    return [
        'published_at' => Carbon::now(),
    ];
});

$factory->state(GoldenBookPost::class, 'unpublished', function () {
    return [
        'published_at' => null,
    ];
});
