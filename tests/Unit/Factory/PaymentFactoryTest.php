<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Payment;
use App\Cart;

class PaymentFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_create_a_payment_with_factory()
    {
        $cart = factory(Cart::class)->create();

        $payment = factory(Payment::class)->create([
            'cart_id' => $cart->cart_id,
        ]);

        $this->assertDatabaseHas('payments', [
            'payment_id' => $payment->payment_id,
            'msisdn' => $payment->msisdn,
            'country_code' => $payment->country_code
        ]);
    }
}
