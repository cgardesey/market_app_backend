<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProvisionTag;
use App\ProvisionTaggable;
use Faker\Generator as Faker;

$factory->define(ProvisionTaggable::class, function (Faker $faker) {
    return [
        'provision_taggable_id' => $faker->unique()->uuid,
        'provision_taggable_type' => $faker->randomElement(['App\\Model1', 'App\\Model2', 'App\\Model3']), // Replace with your actual model class names
        'provision_tag_id' => function () {
            return ProvisionTag::pluck('provision_tag_id')->random();
        },
    ];
});
