<?php

use Faker\Generator as Faker;

$factory->define(App\Genre::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->words($nb = 2, $asText = true)
    ];
});
