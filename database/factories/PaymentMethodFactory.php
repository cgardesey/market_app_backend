<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PaymentMethod;
use Faker\Generator as Faker;

$factory->define(PaymentMethod::class, function (Faker $faker) {
    return [
        'payment_method_id' => $this->faker->unique()->uuid,
        'title' => $this->faker->word,
        'description' => $this->faker->paragraph
    ];
});
