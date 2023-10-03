<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Service;
use App\ServiceImage;
use Faker\Generator as Faker;

$factory->define(ServiceImage::class, function (Faker $faker) {
    return [
        'service_image_id' => $faker->unique()->uuid,
        'url' => $faker->imageUrl(),
        'name' => $faker->word,
        'description' => $faker->sentence,
        'featured_image' => $faker->boolean,
        'service_id' => function () {
            return factory(Service::class)->create()->service_id;
        },
        'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
        'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
    ];
});
