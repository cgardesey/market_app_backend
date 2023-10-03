<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Provider;
use App\Service;
use App\ServiceCategory;
use Faker\Generator as Faker;

$factory->define(Service::class, function (Faker $faker) {
    return [
        'service_id' => $this->faker->unique()->uuid,
        'service_category' => function () {
            return factory(ServiceCategory::class)->create()->service_category;
        },
        'name' => $this->faker->words(3, true),
        'description' => $this->faker->paragraph,
        'min_charge_amount' => $this->faker->randomFloat(2, 0, 9999),
        'max_charge_amount' => $this->faker->randomFloat(2, 0, 9999),
        'rating' => $this->faker->randomFloat(1, 0, 5),
        'total_rating' => $this->faker->randomNumber(),
        'provider_id' => function () {
            return factory(Provider::class)->create()->provider_id;
        },
    ];
});
