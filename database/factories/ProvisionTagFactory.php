<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProvisionTag;
use Faker\Generator as Faker;

$factory->define(ProvisionTag::class, function (Faker $faker) {
    return [
        'provision_tag_id' => $this->faker->unique()->uuid,
        'name' => $this->faker->word,
        'description' => $this->faker->paragraph,
    ];
});
