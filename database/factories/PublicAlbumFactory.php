<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use App\Models\Album;
use App\Models\PublicAlbum;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$withMedias = false;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(PublicAlbum::class, function (Faker $faker) use (&$withMedias) {
    $withMedias = false;

    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph($faker->numberBetween(42, 420)),
        'published_at' => Carbon::now(),
        'created_at' => Carbon::now(),
        'private' => false,
    ];
});

$factory->afterMaking(PublicAlbum::class, function (Album $album, Faker $faker) use (&$withMedias) {
    if ($withMedias) {
        foreach (range(1, 5) as $i) {
            $album->addMediaFromUrl($faker->imageUrl(640 * $faker->numberBetween(1, 3), 480 * $faker->numberBetween(1, 3)))
                ->toMediaCollection(Album::PICTURES_COLLECTION);
        }
    }
});

$factory->state(PublicAlbum::class, 'withMedias', function () use (&$withMedias) {
    $withMedias = true;

    return [];
});

$factory->state(PublicAlbum::class, 'published', function () {
    return [
        'published_at' => Carbon::now(),
    ];
});

$factory->state(PublicAlbum::class, 'unpublished', function () {
    return [
        'published_at' => null,
    ];
});

$factory->state(PublicAlbum::class, 'password', function () {
    return [
        'private' => true,
    ];
});

$factory->state(PublicAlbum::class, 'passwordLess', function () {
    return [
        'private' => false,
    ];
});

$factory->state(PublicAlbum::class, 'withUser', function () {
    return [
        'user_id' => factory(\App\Models\User::class)->create()->id,
    ];
});
