<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cart;
use App\Payment;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'payment_id' => $this->faker->unique()->uuid,
        'msisdn' => $this->faker->phoneNumber,
        'country_code' => $this->faker->countryCode,
        'network' => $this->faker->word,
        'currency' => $this->faker->currencyCode,
        'amount' => $this->faker->randomFloat(2, 0, 999999.99),
        'description' => $this->faker->sentence,
        'payment_ref' => $this->faker->word,
        'message' => $this->faker->sentence,
        'response_message' => $this->faker->sentence,
        'status' => $this->faker->word,
        'external_reference_no' => $this->faker->word,
        'transaction_status_reason' => $this->faker->sentence,
        'cart_id' => function () {
            return factory(Cart::class)->create()->cart_id;
        },
    ];
});
