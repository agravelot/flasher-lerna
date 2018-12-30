<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

use Faker\Generator as Faker;

$factory->define(App\Models\Album::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph($faker->numberBetween(42, 420)),
    ];
});

$factory->state(\App\Models\Album::class, 'published', function () {
    return [
        'published_at' => true,
    ];
});

$factory->state(\App\Models\Album::class, 'unpublished', function () {
    return [
        'published_at' => null,
    ];
});

$factory->state(\App\Models\Album::class, 'password', function () {
    return [
        'password' => 'secret',
    ];
});

$factory->state(\App\Models\Album::class, 'passwordLess', function () {
    return [
        'password' => null,
    ];
});

$factory->state(\App\Models\Album::class, 'withUser', function () {
    return [
        'user_id' => factory(\App\Models\User::class)->create()->id,
    ];
});
