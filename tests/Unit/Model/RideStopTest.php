<?php

namespace Tests\Unit;

use App\RideStop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RideStopTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $rideStop = new RideStop();
        $this->assertEquals('ride_stops', $rideStop->getTable());
    }

    /** @test */
    public function it_uses_rideStop_id_as_the_primary_key()
    {
        $rideStop = new RideStop();

        $this->assertEquals('ride_stop_id', $rideStop->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $rideStop = new RideStop();

        $this->assertFalse($rideStop->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_stop()
    {
        $rideStop = new RideStop();
        $this->assertEquals('string', $rideStop->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $rideStop = new RideStop();
        $hidden = ['id'];
        $this->assertEquals($hidden, $rideStop->getHidden());
    }

    /** @test */
    public function it_returns_rideStop_id_for_route_key()
    {
        $rideStop = new RideStop();

        $this->assertEquals('ride_stop_id', $rideStop->getRouteKeyName());
    }
}
