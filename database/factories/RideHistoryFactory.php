<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use App\RideHistory;
use App\Service;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(RideHistory::class, function (Faker $faker) {
    return [
        'ride_history_id' => Str::uuid()->toString(),
        'start_time' => $this->faker->dateTimeBetween('-2 years', 'now'),
        'end_time' => $this->faker->dateTimeBetween('-2 years', 'now'),
        'pickup_longitude' => $this->faker->longitude(),
        'pickup_latitude' => $this->faker->latitude(),
        'destination_longitude' => $this->faker->longitude(),
        'destination_latitude' => $this->faker->latitude(),
        'pickup_address' => $this->faker->address,
        'destination_address' => $this->faker->address,
        'one_star' => $this->faker->boolean(),
        'two_star' => $this->faker->boolean(),
        'three_star' => $this->faker->boolean(),
        'four_star' => $this->faker->boolean(),
        'five_star' => $this->faker->boolean(),
        'review' => $this->faker->paragraph(),
        'ride_cancelled' => $this->faker->randomElement([-1, 0, 1]),
        'service_id' => function () {
            return factory(Service::class)->create()->service_id;
        },
        'customer_id' => function () {
            return factory(Customer::class)->create()->customer_id;
        },
    ];
});
