<?php

use App\Models\Album;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Factory;

$withMedias = false;

/* @var Factory $factory */
$factory->define(Album::class, static function (Faker $faker) use (&$withMedias) {
    $withMedias = false;

    return [
        'title' => $faker->sentence,
        'body' => $faker->randomHtml($faker->numberBetween(2, 6)),
        'published_at' => null,
        'private' => $faker->boolean,
    ];
});

$factory->afterMaking(Album::class, static function (Album $album, Faker $faker) use (&$withMedias) {
    if ($withMedias) {
        foreach (range(1, 15) as $i) {
            $album->addPicture(UploadedFile::fake()->image('fake.jpg'));
//            $album->addMediaFromUrl(
//                $faker->imageUrl(
//                    640 * $faker->numberBetween(1, 3),
//                    480 * $faker->numberBetween(1, 3))
//            )->toMediaCollection(Album::PICTURES_COLLECTION);
        }
    }
});

$factory->state(Album::class, 'withMedias', static function () use (&$withMedias) {
    $withMedias = true;

    return [];
});

$factory->state(Album::class, 'published', static function () {
    return [
        'published_at' => Carbon::now(),
    ];
});

$factory->state(Album::class, 'unpublished', static function () {
    return [
        'published_at' => null,
    ];
});

$factory->state(Album::class, 'password', static function () {
    return [
        'private' => true,
    ];
});

$factory->state(Album::class, 'passwordLess', static function () {
    return [
        'private' => false,
    ];
});

$factory->state(Album::class, 'withUser', static function () {
    return [
        'user_id' => factory(\App\Models\User::class)->create()->id,
    ];
});
