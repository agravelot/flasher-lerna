<?php

use App\Models\Album;
use App\Models\PublicAlbum;
use App\Services\Keycloak\UserRepresentation;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

$withMedias = false;

/* @var Factory $factory */

$define = static function (Faker $faker) use (&$withMedias) {
    $withMedias = false;

    return [
        'title' => $faker->sentence,
        'meta_description' => $faker->text(120),
        'body' => $faker->randomHtml($faker->numberBetween(2, 6)),
        'published_at' => null,
        'private' => $faker->boolean,
        'sso_id' => Str::uuid(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
};

$factory->define(Album::class, $define);
$factory->define(PublicAlbum::class, $define);

$afterMaking = static function (Album $album, Faker $faker) use (&$withMedias) {
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
};

$factory->afterMaking(Album::class, $afterMaking);
$factory->afterMaking(PublicAlbum::class, $afterMaking);

$withMediaState = static function () use (&$withMedias) {
    $withMedias = true;

    return [];
};

$factory->state(Album::class, 'withMedias', $withMediaState);
$factory->state(PublicAlbum::class, 'withMedias', $withMediaState);

$publishedState = static function () {
    return [
        'published_at' => Carbon::now(),
    ];
};

$factory->state(Album::class, 'published', $publishedState);
$factory->state(PublicAlbum::class, 'published', $publishedState);

$unpublishedState = static function () {
    return [
        'published_at' => null,
    ];
};
$factory->state(Album::class, 'unpublished', $unpublishedState);
$factory->state(PublicAlbum::class, 'unpublished', $unpublishedState);

$passwordState = static function () {
    return [
        'private' => true,
    ];
};

$factory->state(Album::class, 'password', $passwordState);
$factory->state(PublicAlbum::class, 'password', $passwordState);

$passwordLessState = static function () {
    return [
        'private' => false,
    ];
};

$factory->state(Album::class, 'passwordLess', $passwordLessState);
$factory->state(PublicAlbum::class, 'passwordLess', $passwordLessState);

$withUserState = static function (Faker $faker) {
    // TODO Use custom factory ?
    $user = new UserRepresentation();
    $user->email = $faker->email;
    $user->username = $faker->userName;
    $user->emailVerified = true;
    Keycloak::users()->create($user);
    return [
        'sso_id' =>  \App\Facades\Keycloak::users()->all()[0]->id,
    ];
};

$factory->state(Album::class, 'withUser', $withUserState);
$factory->state(PublicAlbum::class, 'withUser', $withUserState);
