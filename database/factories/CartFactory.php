<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cart;
use App\Customer;
use App\Provider;
use Faker\Generator as Faker;

$factory->define(Cart::class, function (Faker $faker) {
    return [
        'cart_id' => $faker->unique()->uuid,
        'order_id' => $faker->uuid,
        'provider_id' => function () {
            return factory(Provider::class)->create()->provider_id;
        },
        'customer_id' => function () {
            return factory(Customer::class)->create()->customer_id;
        },
        'status' => $faker->randomElement(['pending', 'processing', 'completed']),
        'shipping_fee' => $faker->randomFloat(2, 0, 1000),
        'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
        'updated_at' => $faker->dateTimeBetween('-2 years', 'now'),
    ];
});
