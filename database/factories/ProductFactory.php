<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\Provider;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'product_id' => $this->faker->uuid,
        'product_category' => $this->faker->word,
        'name' => $this->faker->word,
        'description' => $this->faker->paragraph,
        'unit_quantity' => $this->faker->numberBetween(0, 100),
        'quantity_available' => $this->faker->numberBetween(0, 1000),
        'unit_price' => $this->faker->randomFloat(2, 0, 1000),
        'rating' => $this->faker->randomFloat(1, 0, 5),
        'total_rating' => $this->faker->numberBetween(0, 1000),
        'provider_id' => function () {
            return factory(Provider::class)->create()->provider_id;
        },
    ];
});
