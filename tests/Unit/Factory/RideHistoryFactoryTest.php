<?php

namespace Tests\Unit\Factories;

use App\Customer;
use App\RideHistory;
use App\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class RideHistoryFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_creates_a_ride_history_with_valid_data()
    {
        $rideHistory = factory(RideHistory::class)->create();

        $this->assertInstanceOf(RideHistory::class, $rideHistory);
        $this->assertDatabaseHas('ride_histories', [
            'ride_history_id' => $rideHistory->ride_history_id,
            'start_time' => $rideHistory->start_time,
            'end_time' => $rideHistory->end_time,
            'service_id' => $rideHistory->service_id,
            'customer_id' => $rideHistory->customer_id,
        ]);
    }
}
