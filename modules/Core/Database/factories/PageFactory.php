<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

use Faker\Generator as Faker;
use Modules\Core\Entities\Page;

$factory->define(Page::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'title' => $faker->sentence,
        'description' => $faker->text,
    ];
});
