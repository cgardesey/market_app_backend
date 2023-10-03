<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\ProductRatingController;
use App\Product;
use App\ProductRating;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductRatingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test storing a newly created resource in storage.
     *
     * @return void
     */
    public function testStore()
    {
        $requestData = factory(ProductRating::class)->make()->toArray();

        $user = factory(User::class)->create();
        $response = $this->post('/api/product-ratings', $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'review' => $requestData['review']
        ]);

        $this->assertDatabaseHas('product_ratings', [
            'review' => $requestData['review']
        ]);
    }
}
