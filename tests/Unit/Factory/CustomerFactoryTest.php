<?php

namespace Tests\Unit\Factories;

use App\Customer;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_customer_factory_creates_customer()
    {
        $user = factory(User::class)->create();

        $attributes = [
            'customer_id' => $this->faker->unique()->uuid,
            'confirmation_token' => Str::random(64),
            'profile_image_url' => $this->faker->url,
            'name' => $this->faker->name,
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'primary_contact' => $this->faker->phoneNumber,
            'auxiliary_contact' => $this->faker->phoneNumber,
            'live_longitude' => $this->faker->longitude,
            'live_latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'digital_address' => $this->faker->word,
            'street_address' => $this->faker->address,
            'user_id' => $user->user_id,
        ];

        $customer = factory(Customer::class)->create($attributes);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertDatabaseHas('customers', $attributes);
    }
}
