<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProductCategory;
use Faker\Generator as Faker;

$factory->define(ProductCategory::class, function (Faker $faker) {
    return [
        'product_category_id' => $this->faker->unique()->uuid,
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'url' => $this->faker->url,
        'tag' => $this->faker->word,
    ];
});
