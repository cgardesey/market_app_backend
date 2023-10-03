<?php

use App\Cart;
use App\Customer;
use App\Product;
use App\Provider;
use App\User;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the scopedCarts method.
     */
    public function testScopedCarts()
    {
        $user = factory(User::class)->create();
        $provider_id = factory(Provider::class)->create()->provider_id;
        $customer_id = factory(Customer::class)->create()->customer_id;
        $product_id = factory(Product::class)->create()->product_id;

        $quantity = 2;
        $price = 10.99;

        $this->addToCart($provider_id, $customer_id, $product_id, $user,$quantity, $price);

        $requestData = ['customer_id' => $customer_id];
        $response = $this->post('/api/scoped-carts',
            $requestData,
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        $response->assertStatus(200);

        $response->assertJsonFragment($requestData);
    }

    /**
     * Test the store method.
     */
    public function testStore()
    {
        $user = factory(User::class)->create();
        $provider_id = factory(Provider::class)->create()->provider_id;
        $customer_id = factory(Customer::class)->create()->customer_id;
        $product_id = factory(Product::class)->create()->product_id;
        $quantity = 2;
        $price = 10.99;
        $response = $this->addToCart($provider_id, $customer_id, $product_id, $user,$quantity, $price);
        $response->assertStatus(200);

        $response->assertJsonFragment([
            'success' => true,
            'provider_id' => $provider_id,
            'customer_id' => $customer_id
        ]);

        $this->assertDatabaseHas('carts', [
            'provider_id' => $provider_id,
            'customer_id' => $customer_id
        ]);

        $this->assertDatabaseHas('cart_products', [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $price,
        ]);
    }

    /**
     * Test the update method.
     */
    public function testUpdate()
    {
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create();

        $updatedData = [
            'status' => 'completed',
            'shipping_fee' => "200",
        ];

        $response = $this->patch("/api/carts/{$cart->cart_id}", $updatedData,
            ['Authorization' => 'Bearer ' . $user->api_token]);

        $response->assertStatus(200);

        $response->assertJsonFragment($updatedData);

        $this->assertDatabaseHas('carts', $updatedData);
    }

    private function addToCart($provider_id, $customer_id, $product_id, $user,$quantity, $price): \Illuminate\Foundation\Testing\TestResponse
    {
        return $this->post('/api/carts', [
            'provider_id' => $provider_id,
            'customer_id' => $customer_id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $price,
        ],
            ['Authorization' => 'Bearer ' . $user->api_token]
        );
    }
}

