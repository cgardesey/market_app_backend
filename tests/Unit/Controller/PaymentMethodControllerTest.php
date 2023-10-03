<?php

namespace Tests\Unit\Controllers;

use App\PaymentMethod;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method of PaymentMethodController.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = factory(User::class)->create();

        $paymentMethods = factory(PaymentMethod::class, 3)->create();

        $response = $this->get('/api/payment-methods', [
            'Authorization' => 'Bearer ' . $user->api_token,
        ]);

        $response->assertStatus(200);


        $response->assertJsonFragment([
            'payment_method_id' => $paymentMethods[0]->payment_method_id,
            'title' => $paymentMethods[0]->title,
            'description' => $paymentMethods[0]->description
        ]);
    }

    /**
     * Test store method of PaymentMethodController.
     *
     * @return void
     */
    public function testStore()
    {
        $requestData = [
            'title' => "Cart",
            'description' => "Cart payment"
        ];

        $user = factory(User::class)->create();
        $response = $this->post('/api/payment-methods', $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment($requestData);

        $this->assertDatabaseHas('payment_methods', $requestData);
    }

    /**
     * Test show method of PaymentMethodController.
     *
     * @return void
     */
    public function testShow()
    {
        $paymentMethod = factory(PaymentMethod::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/payment-methods/' . $paymentMethod->payment_method_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'payment_method_id' => $paymentMethod->payment_method_id,
            'title' => $paymentMethod->title,
            'description' => $paymentMethod->description
        ]);
    }

    /**
     * Test update method of PaymentMethodController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $paymentMethod = factory(PaymentMethod::class)->create();
        $paymentMethod->makeVisible('payment_method_id');

        $requestData = [
            'title' => "Cart",
            'description' => "Cart payment"
        ];

        $user = factory(User::class)->create();

        $response = $this->patch('/api/payment-methods/' . $paymentMethod->payment_method_id, $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment($requestData);

        $paymentMethod->refresh();

        $this->assertDatabaseHas('payment_methods', $requestData);
    }
}
