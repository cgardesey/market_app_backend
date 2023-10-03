<?php

namespace Tests\Unit;

use App\RideHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RideHistoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $rideHistory = new RideHistory();
        $this->assertEquals('ride_histories', $rideHistory->getTable());
    }

    /** @test */
    public function it_uses_rideHistory_id_as_the_primary_key()
    {
        $rideHistory = new RideHistory();

        $this->assertEquals('ride_history_id', $rideHistory->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $rideHistory = new RideHistory();

        $this->assertFalse($rideHistory->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_history()
    {
        $rideHistory = new RideHistory();
        $this->assertEquals('string', $rideHistory->getKeyType());
    }


    /** @test */
    public function it_returns_rideHistory_id_for_route_key()
    {
        $rideHistory = new RideHistory();

        $this->assertEquals('ride_history_id', $rideHistory->getRouteKeyName());
    }
}
