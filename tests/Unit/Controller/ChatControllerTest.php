<?php

namespace Tests\Feature;

use App\Chat;
use App\Customer;
use App\Instructor;
use App\Provider;
use App\Student;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testScopedChats()
    {
        $user = factory(User::class)->create();
        $chat = factory(Chat::class)->create();

        $response = $this->post('/api/scoped-chats', [
            'provider_id' => $chat->provider_id,
            'customer_id' => $chat->customer_id,
            'id' => 0
        ],
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'provider_id' => $chat->provider_id,
            'customer_id' => $chat->customer_id,
        ]);
    }

    /*public function testScopedLatestChats()
    {
        $user = factory(User::class)->create();
        $customer_id = factory(Customer::class)->create()->customer_id;
        $provider_id = factory(Provider::class)->create()->provider_id;

        factory(Chat::class, 3)->create([
            'customer_id' => $customer_id,
            'provider_id' => $provider_id
        ]);

        $response = $this->post('/api/scoped-latest-chats', [
            'customer_id' => $customer_id,
            'provider_id' => $provider_id
        ],
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'customer_id' => $customer_id
        ]);
    }*/
}
