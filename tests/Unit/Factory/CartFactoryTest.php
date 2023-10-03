<?php

namespace Tests\Unit\Factories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factory;
use App\Cart;
use App\Provider;
use App\Customer;

class CartFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var Factory */
    protected $cartFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartFactory = app(Factory::class)->of(Cart::class);
    }

    /** @test */
    public function it_can_create_a_cart()
    {
        // Create a Provider and Customer to be associated with the Cart
        $provider = factory(Provider::class)->create();
        $customer = factory(Customer::class)->create();

        // Create a Cart using the factory and override some attributes if necessary
        $cart = $this->cartFactory->create([
            'provider_id' => $provider->provider_id,
            'customer_id' => $customer->customer_id,
        ]);

        // Assert the created Cart has the correct attributes
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertDatabaseHas('carts', [
            'cart_id' => $cart->cart_id,
            'order_id' => $cart->order_id,
            'provider_id' => $provider->provider_id,
            'customer_id' => $customer->customer_id,
            'status' => $cart->status,
            'shipping_fee' => $cart->shipping_fee,
            'created_at' => $cart->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $cart->updated_at->format('Y-m-d H:i:s'),
        ]);
    }
}
