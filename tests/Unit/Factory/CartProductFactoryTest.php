<?php

namespace Tests\Unit;

use App\Cart;
use App\CartProduct;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartProductFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_product_factory_creates_valid_cart_product()
    {
        // Create a CartProduct using the factory
        $cartProduct = factory(CartProduct::class)->create();

        // Assert that the created CartProduct has the correct attributes
        $this->assertInstanceOf(CartProduct::class, $cartProduct);
        $this->assertNotEmpty($cartProduct->cart_product_id);
        $this->assertNotEmpty($cartProduct->cart_id);
        $this->assertNotEmpty($cartProduct->product_id);
        $this->assertGreaterThanOrEqual(1, $cartProduct->quantity);
        $this->assertLessThanOrEqual(10, $cartProduct->quantity);
        $this->assertGreaterThanOrEqual(10, $cartProduct->price);
        $this->assertLessThanOrEqual(100, $cartProduct->price);
    }
}
