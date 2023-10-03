<?php

namespace Tests\Unit\Factories;

use App\RideHistory;
use App\RideStop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RideStopFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_creates_a_ride_stop_with_valid_data()
    {
        $rideStop = factory(RideStop::class)->create();

        $this->assertInstanceOf(RideStop::class, $rideStop);
        $this->assertDatabaseHas('ride_stops', [
            'ride_stop_id' => $rideStop->ride_stop_id,
            'latitude' => $rideStop->latitude,
            'longitude' => $rideStop->longitude,
            'ride_history_id' => $rideStop->ride_history_id,
        ]);
    }
}
