<?php

use Faker\Generator as Faker;

$factory->define(App\PaymentType::class, function (Faker $faker) {
    return [
        'name' => $faker->words($nb = 3, $asText = true),
    ];
});
