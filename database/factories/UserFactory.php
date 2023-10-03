<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'user_id' => Str::uuid(),
        'phone_number' => $this->faker->phoneNumber,
        'username' => $this->faker->userName,
        'email' => $this->faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'confirmation_token' => Str::random(64),
        'password' => bcrypt('password'), // Default password, change as needed.
        'api_token' => Str::random(80),
        'role' => 'user', // Default role, change as needed.
        'email_verified' => 1, // Default value for email_verified.
        'active' => true, // Default value for active.
        'connected' => false, // Default value for connected.
        'otp' => null,
        'app_hash' => null,
        'os_version' => null,
        'sdk_version' => null,
        'device' => null,
        'device_model' => null,
        'device_product' => null,
        'manufacturer' => null,
        'android_id' => null,
        'version_release' => null,
        'device_height' => null,
        'device_width' => null,
        'guid' => null,
    ];
});
