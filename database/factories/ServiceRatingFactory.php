<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Service;
use App\ServiceRating;
use Faker\Generator as Faker;

$factory->define(ServiceRating::class, function (Faker $faker) {
    return [
        'service_rating_id' => $this->faker->uuid,
        'service_id' => function () {
            return factory(Service::class)->create()->service_id;
        },
        'one_star' => $this->faker->boolean,
        'two_star' => $this->faker->boolean,
        'three_star' => $this->faker->boolean,
        'four_star' => $this->faker->boolean,
        'five_star' => $this->faker->boolean,
        'review' => $this->faker->paragraph(2),
    ];
});
