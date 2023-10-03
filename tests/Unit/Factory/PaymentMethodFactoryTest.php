<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\PaymentMethod;

class PaymentMethodFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_create_a_payment_method()
    {
        $paymentMethod = factory(PaymentMethod::class)->create();

        $this->assertDatabaseHas('payment_methods', [
            'payment_method_id' => $paymentMethod->payment_method_id,
            'title' => $paymentMethod->title,
            'description' => $paymentMethod->description,
        ]);
    }
}
