<?php

namespace Tests\Unit;

use App\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $paymentMethod = new PaymentMethod();
        $this->assertEquals('payment_methods', $paymentMethod->getTable());
    }

    /** @test */
    public function it_uses_paymentMethod_id_as_the_primary_key()
    {
        $paymentMethod = new PaymentMethod();

        $this->assertEquals('payment_method_id', $paymentMethod->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $paymentMethod = new PaymentMethod();

        $this->assertFalse($paymentMethod->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_method()
    {
        $paymentMethod = new PaymentMethod();
        $this->assertEquals('string', $paymentMethod->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $paymentMethod = new PaymentMethod();
        $hidden = ['id'];
        $this->assertEquals($hidden, $paymentMethod->getHidden());
    }

    /** @test */
    public function it_returns_paymentMethod_id_for_route_key()
    {
        $paymentMethod = new PaymentMethod();

        $this->assertEquals('payment_method_id', $paymentMethod->getRouteKeyName());
    }
}
