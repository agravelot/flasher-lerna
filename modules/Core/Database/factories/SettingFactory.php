<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use Faker\Generator as Faker;
use Modules\Core\Entities\Setting;

$factory->define(Setting::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'name' => $faker->word,
        'value' => $faker->boolean,
    ];
});
