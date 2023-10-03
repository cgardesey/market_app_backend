<?php

namespace Tests\Unit;

use App\Customer;
use App\User;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use App\Http\Controllers\CustomerController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the show method of CustomerController.
     *
     * @return void
     */
    public function testShow()
    {
        $customer = factory(Customer::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/customers/' . $customer->customer_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        Log::info('message', [$response]);

        $response->assertJsonFragment([
            'profile_image_url' => $customer->profile_image_url,
            'name' => $customer->name,
            'gender' => $customer->gender,
            'primary_contact' => $customer->primary_contact,
            'auxiliary_contact' => $customer->auxiliary_contact,
        ]);
    }

    public function testStoreMethod()
    {
        // Disable exception handling to see the detailed error messages, if any.
        $this->withoutExceptionHandling();


        $requestData = factory(Customer::class)->make()->toArray();

        $user = factory(User::class)->create();
        $response = $this->post('/api/customers', $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        // Assert that the response is successful (status code 200)
        $response->assertOk();

        // Assert that the response contains the expected data
        $response->assertJsonStructure([
            'customer' => ['customer_id'],
            'banners' => [],
            'service_categories' => [],
            'physical_address',
        ]);

        // Assert that the customer has been created in the database
        $this->assertDatabaseHas('customers', [
            'name' => $requestData['name'],
        ]);
    }

    public function testUpdate()
    {
        $customer = factory(Customer::class)->create();

        $requestData = factory(Customer::class)->make()->toArray();

        $user = factory(User::class)->create();

        $data = [
            'name' => $requestData['name'],
            'primary_contact' => $requestData['primary_contact'],
            'live_longitude' => "10.0",
            'live_latitude' => "10.0",
            'longitude' => "10.0",
            'latitude' => "10.0"
        ];
        $response = $this->post('/api/customers/' . $customer->customer_id, $data, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => $requestData['name'],
            'primary_contact' => $requestData['primary_contact']
        ]);

        $customer->refresh();

        $this->assertDatabaseHas('customers', [
            'name' => $requestData['name'],
            'primary_contact' => $requestData['primary_contact']
        ]);
    }
}
