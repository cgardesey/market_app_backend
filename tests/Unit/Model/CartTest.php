<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Cart;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $cart = new Cart();
        $this->assertEquals('carts', $cart->getTable());
    }

    /**
     * Test if the primary key is set correctly.
     */
    public function testPrimaryKey()
    {
        $cart = new Cart();
        $this->assertEquals('cart_id', $cart->getKeyName());
    }

    /**
     * Test if the primary key is not auto-incrementing.
     */
    public function testPrimaryKeyIncrementing()
    {
        $cart = new Cart();
        $this->assertFalse($cart->getIncrementing());
    }

    /**
     * Test if the primary key type is set to 'string'.
     */
    public function testPrimaryKeyType()
    {
        $cart = new Cart();
        $this->assertEquals('string', $cart->getKeyType());
    }

    /**
     * Test if the guarded properties are set correctly.
     */
    public function testGuardedProperties()
    {
        $cart = new Cart();
        $guarded = ['id', 'cart_id'];
        $this->assertEquals($guarded, $cart->getGuarded());
    }

    /**
     * Test if the route key name is set correctly.
     */
    public function testRouteKeyName()
    {
        $cart = new Cart();
        $this->assertEquals('cart_id', $cart->getRouteKeyName());
    }
}
