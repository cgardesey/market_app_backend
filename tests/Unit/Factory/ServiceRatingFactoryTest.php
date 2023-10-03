<?php

namespace Tests\Unit\Factories;

use App\ServiceRating;
use App\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceRatingFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_creates_a_valid_service_rating_instance()
    {
        $serviceRating = factory(ServiceRating::class)->create();

        $this->assertInstanceOf(ServiceRating::class, $serviceRating);
        $this->assertDatabaseHas('service_ratings', [
            'service_rating_id' => $serviceRating->service_rating_id,
            'service_id' => $serviceRating->service_id,
            'one_star' => $serviceRating->one_star,
            'two_star' => $serviceRating->two_star,
            'three_star' => $serviceRating->three_star,
            'four_star' => $serviceRating->four_star,
            'five_star' => $serviceRating->five_star,
            'review' => $serviceRating->review,
        ]);
    }
}
