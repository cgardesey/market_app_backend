<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\ProductImage;
use Faker\Generator as Faker;
use Psy\Util\Str;

$factory->define(ProductImage::class, function (Faker $faker) {
    return [
        'product_image_id' => $this->faker->uuid,
        'url' => $this->faker->imageUrl(), // Assuming you want to generate a random image URL
        'name' => $this->faker->word, // Assuming you want to generate a random word for the name
        'description' => $this->faker->paragraph, // Assuming you want to generate a random paragraph for the description
        'featured_image' => true, // You can set it as false if you need some entries to have 'false' as well
        'product_id' => function () {
            return factory(Product::class)->create()->product_id;
        },
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
