<?php

use App\Adapters\Keycloak\UserQuery;
use App\Adapters\Keycloak\UserRepresentation;
use App\Models\Cosplayer;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/* @var Factory $factory */

$factory->define(Cosplayer::class, static function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        //'sso_id' => $faker->boolean ? Str::uuid() : null,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->state(Cosplayer::class, 'avatar', static function () {
    return [
        'avatar' => UploadedFile::fake()->image('fake.jpg'),
    ];
});

$factory->state(Cosplayer::class, 'withUser', static function (Faker $faker) {
    $user = UserRepresentation::factory();
    Keycloak::users()->create($user);

    $query = new UserQuery();
    $query->email = $user->email;

    return [
        'sso_id' => Keycloak::users()->first($query)->id,
    ];
});
