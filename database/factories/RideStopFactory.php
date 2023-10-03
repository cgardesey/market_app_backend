<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\RideHistory;
use App\RideStop;
use Faker\Generator as Faker;

$factory->define(RideStop::class, function (Faker $faker) {
    return [
        'ride_stop_id' => $this->faker->unique()->uuid,
        'latitude' => $this->faker->latitude,
        'longitude' => $this->faker->longitude,
        'destination' => $this->faker->city,
        'tag' => $this->faker->randomElement(['tag1', 'tag2', 'tag3', null]),
        'ride_history_id' => function () {
            return factory(RideHistory::class)->create()->ride_history_id;
        },
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
