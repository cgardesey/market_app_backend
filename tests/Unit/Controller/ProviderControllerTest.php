<?php

namespace Tests\Unit;

use App\Provider;
use App\User;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProviderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the show method of ProviderController.
     *
     * @return void
     */
    public function testShow()
    {
        $provider = factory(Provider::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/providers/' . $provider->provider_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        Log::info('message', [$response]);

        $response->assertJsonFragment([
            'profile_image_url' => $provider->profile_image_url,
            'first_name' => $provider->first_name,
            'last_name' => $provider->last_name,
            'other_name' => $provider->other_name,
            'gender' => $provider->gender,
            'primary_contact' => $provider->primary_contact,
            'auxiliary_contact' => $provider->auxiliary_contact
        ]);
    }

    /*public function testStoreMethod()
    {
        $this->assertTrue(true);
    }*/

    public function testUpdate()
    {
        $provider = factory(Provider::class)->create();

        $requestData = factory(Provider::class)->make()->toArray();

        $user = factory(User::class)->create();

        $response = $this->post('/api/providers/' . $provider->provider_id, [
            'first_name' => $requestData['first_name'],
            'last_name' => $requestData['last_name'],
            'other_name' => $requestData['other_name'],
            'primary_contact' => $requestData['primary_contact'],
            'live_longitude' => "10.0",
            'live_latitude' => "10.0",
            'longitude' => "10.0",
            'latitude' => "10.0"
        ], [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'first_name' => $requestData['first_name'],
            'last_name' => $requestData['last_name'],
            'other_name' => $requestData['other_name'],
            'primary_contact' => $requestData['primary_contact'],
        ]);

        $provider->refresh();

        $this->assertDatabaseHas('providers', [
            'first_name' => $requestData['first_name'],
            'last_name' => $requestData['last_name'],
            'other_name' => $requestData['other_name'],
            'primary_contact' => $requestData['primary_contact']
        ]);
    }
}
