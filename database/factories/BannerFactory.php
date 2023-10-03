<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Banner;
use Faker\Generator as Faker;

$factory->define(Banner::class, function (Faker $faker) {
    return [
        'banner_id' => $this->faker->uuid,
        'title' => $this->faker->sentence,
        'url' => $this->faker->url,
        'tag' => $this->faker->word
    ];
});
