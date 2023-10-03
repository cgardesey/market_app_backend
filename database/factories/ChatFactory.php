<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Chat;
use App\Customer;
use App\Provider;
use Faker\Generator as Faker;

$factory->define(Chat::class, function (Faker $faker) {
    return [
        'chat_id' => $faker->unique()->uuid,
        'chat_ref_id' => $faker->uuid,
        'temp_id' => $faker->uuid,
        'text' => $faker->sentence,
        'link' => $faker->url,
        'link_title' => $faker->sentence,
        'link_description' => $faker->paragraph,
        'link_image' => $faker->imageUrl(),
        'attachment_url' => $faker->url,
        'attachment_type' => $faker->word,
        'attachment_title' => $faker->sentence,
        'read_by_recipient' => $faker->boolean,
        'sent_by_customer' => $faker->boolean,
        'tag' => $faker->word,
        'customer_id' => function () {
            return factory(Customer::class)->create()->customer_id;
        },
        'provider_id' => function () {
            return factory(Provider::class)->create()->provider_id;
        },
        'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
        'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
    ];
});
