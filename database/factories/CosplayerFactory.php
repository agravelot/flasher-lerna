<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Cosplayer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'created_at' => $faker->dateTime(),
        'updated_at' => Carbon::now(),
    ];
});
