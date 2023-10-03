<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Products;
use App\Provider;
use Faker\Generator as Faker;

$factory->define(Products::class, function (Faker $faker) {
    return [
        'product_id' => $faker->unique()->uuid,
        'product_category' => $faker->word,
        'name' => $faker->word,
        'description' => $faker->paragraph,
        'unit_quantity' => $faker->numberBetween(1, 100),
        'quantity_available' => $faker->numberBetween(0, 50),
        'unit_price' => $faker->randomFloat(2, 10, 1000),
        'rating' => $faker->randomFloat(1, 0, 5),
        'total_rating' => $faker->numberBetween(0, 1000),
        'provider_id' => function () {
            return factory(Provider::class)->create()->provider_id;
        },
        'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
        'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
    ];
});
