<?php

use Carbon\Carbon;
use App\Models\Cosplayer;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(Cosplayer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->state(Cosplayer::class, 'avatar', function () {
    return [
        'avatar' => UploadedFile::fake()->image('fake.jpg'),
    ];
});
