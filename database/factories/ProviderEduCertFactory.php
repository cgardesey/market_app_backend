<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Provider;
use App\ProviderEduCert;
use Faker\Generator as Faker;

$factory->define(ProviderEduCert::class, function (Faker $faker) {
    return [
        'provider_edu_cert_id' => $this->faker->uuid,
        'cert_title' => $this->faker->sentence,
        'institution_name' => $this->faker->company,
        'start_date' => $this->faker->date(),
        'end_date' => $this->faker->date(),
        'provider_id' => function () {
            return factory(Provider::class)->create()->provider_id;
        },
        'deleted' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
