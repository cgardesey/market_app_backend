<?php

namespace Tests\Unit;
use App\ServiceRating;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRatingControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test store method of ServiceRatingController.
     *
     * @return void
     */
    public function testStore()
    {
        $requestData = factory(ServiceRating::class)->make()->toArray();

        $user = factory(User::class)->create();
        $response = $this->post('/api/service-ratings', $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'review' => $requestData['review']
        ]);

        $this->assertDatabaseHas('service_ratings', [
            'review' => $requestData['review']
        ]);
    }
}
