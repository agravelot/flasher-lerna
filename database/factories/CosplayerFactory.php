<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use App\Models\Cosplayer;
use Carbon\Carbon;
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