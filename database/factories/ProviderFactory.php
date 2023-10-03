<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Provider;
use App\User;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {
    return [
        'provider_id' => $this->faker->unique()->uuid,
        'confirmation_token' => null,
        'title' => $this->faker->title,
        'first_name' => $this->faker->firstName,
        'last_name' => $this->faker->lastName,
        'other_name' => $this->faker->firstName,
        'gender' => $this->faker->randomElement(['male', 'female']),
        'dob' => $this->faker->date(),
        'marital_status' => $this->faker->randomElement(['single', 'married', 'divorced']),
        'highest_edu_level' => $this->faker->randomElement(['high school', 'college', 'university']),
        'institution' => $this->faker->word,
        'about' => $this->faker->sentence,
        'provider_name' => $this->faker->name,
        'profile_image_url' => $this->faker->imageUrl,
        'primary_contact' => $this->faker->phoneNumber,
        'auxiliary_contact' => $this->faker->phoneNumber,
        'momo_number' => $this->faker->phoneNumber,
        'postal_address' => $this->faker->address,
        'longitude' => $this->faker->longitude,
        'latitude' => $this->faker->latitude,
        'live_longitude' => $this->faker->longitude,
        'live_latitude' => $this->faker->latitude,
        'digital_address' => $this->faker->word,
        'street_address' => $this->faker->streetAddress,
        'years_of_operation' => $this->faker->numberBetween(1, 10),
        'date_registered' => $this->faker->date(),
        'verified' => $this->faker->boolean,
        'category' => $this->faker->word,
        'identification_type' => $this->faker->word,
        'association_identification_type' => $this->faker->word,
        'identification_number' => $this->faker->randomNumber(),
        'association_identification_number' => $this->faker->randomNumber(),
        'identification_image_url' => $this->faker->imageUrl,
        'association_identification_image_url' => $this->faker->imageUrl,
        'vehicle_type' => $this->faker->word,
        'vehicle_registration_number' => $this->faker->word,
        'drivers_licence_image_url' => $this->faker->imageUrl,
        'drivers_licence_reverse_image_url' => $this->faker->imageUrl,
        'road_worthy_sticker_image_url' => $this->faker->imageUrl,
        'insurance_sticker_image_url' => $this->faker->imageUrl,
        'tin_number' => $this->faker->randomNumber(),
        'availability' => 'Available',
        'user_id' => function () {
            return factory(User::class)->create()->user_id;
        },
    ];
});
