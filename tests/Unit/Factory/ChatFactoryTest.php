<?php

namespace Tests\Unit;

use App\Chat;
use App\Customer;
use App\Provider;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_chat()
    {
        // Create a customer and provider using factories
        $customer = factory(Customer::class)->create();
        $provider = factory(Provider::class)->create();

        // Generate fake data using Faker
        $faker = Factory::create();

        // Data for the chat
        $chatData = [
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
            'customer_id' => $customer->customer_id,
            'provider_id' => $provider->provider_id,
            'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
        ];

        // Create a new chat using the factory
        $chat = factory(Chat::class)->create($chatData);

        // Assert the chat was created successfully
        $this->assertInstanceOf(Chat::class, $chat);

        // Optionally, you can also assert the chat data to check if it matches the provided data
        $this->assertEquals($chatData['chat_id'], $chat->chat_id);
        $this->assertEquals($chatData['chat_ref_id'], $chat->chat_ref_id);
        $this->assertEquals($chatData['temp_id'], $chat->temp_id);
    }
}
