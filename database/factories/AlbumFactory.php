<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

use App\Models\Album;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$withMedias = false;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Album::class, function (Faker $faker) use (&$withMedias) {
    $withMedias = false;

    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph($faker->numberBetween(42, 420)),
        'published_at' => $faker->dateTime,
    ];
});

$factory->afterMaking(Album::class, function (Album $album, Faker $faker) use (&$withMedias) {
    if ($withMedias) {
        foreach (range(1, 5) as $i) {
            $album->addMediaFromUrl($faker->imageUrl())
                ->toMediaCollection('pictures');
        }
    }
});

$factory->state(Album::class, 'withMedias', function () use (&$withMedias) {
    $withMedias = true;

    return [];
});

$factory->state(Album::class, 'published', function () {
    return [
        'published_at' => Carbon::now(),
    ];
});

$factory->state(Album::class, 'unpublished', function () {
    return [
        'published_at' => null,
    ];
});

$factory->state(Album::class, 'password', function () {
    return [
        'password' => 'secret',
    ];
});

$factory->state(Album::class, 'passwordLess', function () {
    return [
        'password' => null,
    ];
});

$factory->state(Album::class, 'withUser', function () {
    return [
        'user_id' => factory(\App\Models\User::class)->create()->id,
    ];
});
