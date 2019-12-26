<?php

use App\Models\Cosplayer;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Http\UploadedFile;

/* @var Factory $factory */

$factory->define(Cosplayer::class, static function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->state(Cosplayer::class, 'avatar', static function () {
    return [
        'avatar' => UploadedFile::fake()->image('fake.jpg'),
    ];
});

$factory->state(Cosplayer::class, 'withUser', static function () {
    return [
        'user_id' => factory(\App\Models\User::class)->create()->id,
    ];
});
