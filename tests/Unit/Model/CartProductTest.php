<?php

namespace Tests\Unit;

use App\CartProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartProductTest extends TestCase
{
    use RefreshDatabase; // This will reset the database after each test case

    /** @test */
    public function it_has_correct_table_name()
    {
        $cartProduct = new CartProduct();
        $this->assertEquals('cart_products', $cartProduct->getTable());
    }

    /** @test */
    public function it_uses_correct_primary_key()
    {
        $cartProduct = new CartProduct();

        $this->assertEquals('cart_product_id', $cartProduct->getKeyName());
        $this->assertFalse($cartProduct->getIncrementing());
        $this->assertEquals('string', $cartProduct->getKeyType());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $cartProduct = new CartProduct();
        $this->assertEquals('string', $cartProduct->getKeyType());
    }

    /** @test */
    public function it_is_guarded_from_mass_assignment()
    {
        $cartProduct = new CartProduct();

        $guarded = $cartProduct->getGuarded();

        $this->assertEquals(['id', 'cart_product_id'], $guarded);
    }

    /** @test */
    public function it_is_hidden_from_json_output()
    {
        $cartProduct = new CartProduct();

        $hidden = $cartProduct->getHidden();

        $this->assertEquals(['id'], $hidden);
    }

    /** @test */
    public function it_gets_correct_route_key_name()
    {
        $cartProduct = new CartProduct();

        $routeKeyName = $cartProduct->getRouteKeyName();

        $this->assertEquals('cart_product_id', $routeKeyName);
    }
}
