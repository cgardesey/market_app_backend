<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\IdentificationType;
use Faker\Generator as Faker;

$factory->define(IdentificationType::class, function (Faker $faker) {
    return [
        'identification_type_id' => $this->faker->uuid,
        'name' => $this->faker->word,
        'description' => $this->faker->paragraph,
    ];
});
