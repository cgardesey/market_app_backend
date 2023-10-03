<?php

namespace Tests\Feature\Http\Controllers;

use App\Cart;
use App\CartProduct;
use App\Customer;
use App\Http\Controllers\CartProductController;
use App\Product;
use App\Provider;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Tests\TestCase;

class CartProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testScopedCartProducts()
    {
        $user = factory(User::class)->create();
        $provider_id = factory(Provider::class)->create()->provider_id;
        $customer_id = factory(Customer::class)->create()->customer_id;
        $product_id = factory(Product::class)->create()->product_id;

        $response = $this->post('/api/carts', [
            'provider_id' => $provider_id,
            'customer_id' => $customer_id,
            'product_id' => $product_id,
            'quantity' => 2,
            'price' => 10.99,
        ],
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        Log::info("resp", $response->json());

        $requestData = [
            'cart_id' => $response->json()['cart']['cart_id'],
            'order_id' => $response->json()['cart']['order_id']
        ];
        $response = $this->post('/api/scoped-cart-products',
            $requestData,
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'cart_product_id',
                'product_id',
                'product_category',
                'url',
                'unit_quantity',
                'quantity_available',
                'unit_price',
            ]
        ]);
    }

    public function testScopedCartTotal()
    {
        $user = factory(User::class)->create();
        $response = $this->post('/api/cart-total', [
            'cart_id' => factory(Cart::class)->create()->cart_id
        ],
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'cart_total',
            'provider' => [
                'provider_id',
                'first_name',
                'last_name',
                'other_name',
                'gender',
                'dob',
                'marital_status',
                'highest_edu_level',
                'institution',
                'about',
            ]
        ]);
    }

    public function testScopedCartTotalCount()
    {
        $user = factory(User::class)->create();
        $response = $this->post('/api/cart-total-count', [
            'cart_id' => factory(Cart::class)->create()->cart_id
        ],
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'cart_total',
            'item_count',
            'provider' => [
                'provider_id',
                'first_name',
                'last_name',
                'other_name',
                'gender',
                'dob',
                'marital_status',
                'highest_edu_level',
                'institution',
                'about'
            ]
        ]);
    }

    public function testUpdate()
    {
        // Create a test CartProduct
        $user = factory(User::class)->create();
        $cartProduct = factory(CartProduct::class)->create();

        // Prepare the update data
        $updatedData = [
            'quantity' => "10",
            'price' => "300",
        ];

        // Perform the update request
        $response = $this->patch("/api/cart-products/{$cartProduct->cart_product_id}", $updatedData,
            ['Authorization' => 'Bearer ' . $user->api_token]);

        // Assert the response has the updated data
        $response->assertStatus(200);
        $response->assertJson($updatedData);

        // Check if the CartProduct was updated in the database
        $this->assertDatabaseHas('cart_products', $updatedData);
    }

    public function testDestroy()
    {
        // Create a test CartProduct
        $user = factory(User::class)->create();
        $cartProduct = factory(CartProduct::class)->create();

        $controller = new CartProductController();

        // Perform the delete request
        $response = $this->delete("/api/cart-products/{$cartProduct->cart_product_id}", [],
            ['Authorization' => 'Bearer ' . $user->api_token]);

        // Assert the response has the status of the delete operation
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true, // Assuming the delete operation was successful
        ]);

        // Check if the CartProduct was deleted from the database
        $this->assertDatabaseMissing('cart_products', [
            'cart_product_id' => $cartProduct->cart_product_id
        ]);
    }
}
