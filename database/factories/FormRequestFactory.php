<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FormRequest;
use Faker\Generator as Faker;

$factory->define(FormRequest::class, function (Faker $faker) {
    $method = array('Cash', 'Transfer');
    $allocation = array('Tabligh', 'Bukber', 'Lomba');
    return [
        'user_id' => 1,
        'date' => $faker->date(),
        'method' => $method[array_rand($method, 1)],
        'allocation' => $allocation[array_rand($allocation, 1)],
        'amount' => $faker->numberBetween(1, 10) * 50000,
        'attachment' => $faker->imageUrl(),
        'notes' => $faker->text
    ];
});
