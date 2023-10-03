<?php

namespace Tests\Unit;

use App\ServiceRating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRatingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $serviceRating = new ServiceRating();
        $this->assertEquals('service_ratings', $serviceRating->getTable());
    }

    /** @test */
    public function it_uses_serviceRating_id_as_the_primary_key()
    {
        $serviceRating = new ServiceRating();

        $this->assertEquals('service_rating_id', $serviceRating->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $serviceRating = new ServiceRating();

        $this->assertFalse($serviceRating->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_rating()
    {
        $serviceRating = new ServiceRating();
        $this->assertEquals('string', $serviceRating->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $serviceRating = new ServiceRating();
        $hidden = ['id'];
        $this->assertEquals($hidden, $serviceRating->getHidden());
    }

    /** @test */
    public function it_returns_serviceRating_id_for_route_key()
    {
        $serviceRating = new ServiceRating();

        $this->assertEquals('service_rating_id', $serviceRating->getRouteKeyName());
    }
}
