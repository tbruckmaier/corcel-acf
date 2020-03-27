<?php

use Corcel\Model\Option;

$factory->define(Option::class, function (Faker\Generator $faker) {
    return [
        'option_id' => $faker->numberBetween(1, 100),
        'option_name' => $faker->word,
        'option_value' => $faker->sentence(),
        'autoload' => 'yes',
    ];
});

