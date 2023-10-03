<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use App\User;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'customer_id' => $this->faker->unique()->uuid,
        'confirmation_token' => Str::random(64),

        'profile_image_url' => $this->faker->url, // By default, nullable
        'name' => $this->faker->name,
        'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
        'primary_contact' => $this->faker->phoneNumber,
        'auxiliary_contact' => $this->faker->phoneNumber,
        'live_longitude' => $this->faker->longitude,
        'live_latitude' => $this->faker->latitude,
        'longitude' => $this->faker->longitude,
        'latitude' => $this->faker->latitude,
        'digital_address' => $this->faker->word,
        'street_address' => $this->faker->address,
        'user_id' => function () {
            return factory(User::class)->create()->user_id;
        },
    ];
});
