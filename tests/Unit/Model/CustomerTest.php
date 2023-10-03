<?php

namespace Tests\Unit;

use App\Customer;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $customer = new Customer();
        $this->assertEquals('customers', $customer->getTable());
    }

    /** @test */
    public function it_uses_customer_id_as_the_primary_key()
    {
        $customer = new Customer();

        $this->assertEquals('customer_id', $customer->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $customer = new Customer();

        $this->assertFalse($customer->getIncrementing());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $customer = new Customer();
        $hidden = ['id'];
        $this->assertEquals($hidden, $customer->getHidden());
    }

    /** @test */
    public function it_returns_customer_id_for_route_key()
    {
        $customer = new Customer();

        $this->assertEquals('customer_id', $customer->getRouteKeyName());
    }

}
