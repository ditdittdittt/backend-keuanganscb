<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Rekening;
use Faker\Generator as Faker;

$factory->define(Rekening::class, function (Faker $faker) {
    return [
        'bank_name' => $faker->firstName(),
        'bank_code' => (string) $faker->numberBetween(100, 999),
        'account_number' => ((string) $faker->randomNumber(9)) . ((string) $faker->randomNumber(3)),
        'account_owner' => $faker->name(),
    ];
});
