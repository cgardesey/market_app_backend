<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cart;
use App\CartProduct;
use App\Product;
use Faker\Generator as Faker;

$factory->define(CartProduct::class, function (Faker $faker) {
    return [
        'cart_product_id' => $this->faker->unique()->uuid,
        'cart_id' => function () {
            return factory(Cart::class)->create()->cart_id;
        },
        'product_id' => function () {
            return factory(Product::class)->create()->product_id;
        },
        'quantity' => $this->faker->numberBetween(1, 10),
        'price' => $this->faker->randomFloat(2, 10, 100),
    ];
});
