<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\ProductRating;
use Faker\Generator as Faker;

$factory->define(ProductRating::class, function (Faker $faker) {
    return [
        'product_rating_id' => $faker->unique()->uuid,
        'product_id' => function () {
            return factory(Product::class)->create()->product_id;
        },
        'one_star' => $faker->boolean,
        'two_star' => $faker->boolean,
        'three_star' => $faker->boolean,
        'four_star' => $faker->boolean,
        'five_star' => $faker->boolean,
        'review' => $faker->paragraph(2),
    ];
});
