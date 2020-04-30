<?php

use App\Models\Album;
use App\Models\PublicAlbum;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/* @var Factory $factory */

$withMedias = false;

$factory->define(PublicAlbum::class, static function (Faker $faker) use (&$withMedias) {
    $withMedias = false;

    return [
        'title' => $faker->sentence,
        'meta_description' => $faker->text(254),
        'body' => $faker->paragraph($faker->numberBetween(42, 420)),
        'sso_id' => Str::uuid(),
        'published_at' => Carbon::now(),
        'created_at' => Carbon::now(),
        'private' => false,
    ];
});

$factory->afterMaking(PublicAlbum::class, static function (Album $album, Faker $faker) use (&$withMedias) {
    if ($withMedias) {
        foreach (range(1, 5) as $i) {
            $album->addMediaFromUrl($faker->imageUrl(640 * $faker->numberBetween(1, 3), 480 * $faker->numberBetween(1, 3)))
                ->toMediaCollection(Album::PICTURES_COLLECTION);
        }
    }
});

$factory->state(PublicAlbum::class, 'withMedias', static function () use (&$withMedias) {
    $withMedias = true;

    return [];
});

$factory->state(PublicAlbum::class, 'published', static function () {
    return [
        'published_at' => Carbon::now(),
    ];
});

$factory->state(PublicAlbum::class, 'unpublished', static function () {
    return [
        'published_at' => null,
    ];
});

$factory->state(PublicAlbum::class, 'password', static function () {
    return [
        'private' => true,
    ];
});

$factory->state(PublicAlbum::class, 'passwordLess', static function () {
    return [
        'private' => false,
    ];
});

$factory->state(PublicAlbum::class, 'withUser', static function () {
    return [
        'sso_id' => Str::uuid(),
    ];
});
