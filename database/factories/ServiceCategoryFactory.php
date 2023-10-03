<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ServiceCategory;
use Faker\Generator as Faker;

$factory->define(ServiceCategory::class, function (Faker $faker) {
    return [
        'service_category_id' => $this->faker->unique()->uuid,
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'url' => $this->faker->url,
        'tag' => $this->faker->word,
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
