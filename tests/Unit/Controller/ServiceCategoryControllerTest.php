<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\ServiceCategoryController;
use App\ServiceCategory;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class ServiceCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method of ServiceCategoryController.
     *
     * @return void
     */
    public function testIndex()
    {
        // Create some test data in the database
        factory(ServiceCategory::class)->create([
            'title' => 'Category A',
            'description' => '',
        ]);

        factory(ServiceCategory::class)->create([
            'title' => 'Category B',
            'description' => 'Description B',
        ]);

        factory(ServiceCategory::class)->create([
            'title' => 'Category C',
            'description' => '',
        ]);
        $user = factory(User::class)->create();
        // Make a GET request to the index method
        $response = $this->get('/api/service-categories', [
            'Authorization' => 'Bearer ' . $user->api_token,
        ]);

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the response contains the correct data from the database
        $response->assertJson([
            [
                'id' => 1,
                'title' => 'Category A',
                'description' => '',
            ],
            [
                'id' => 3,
                'title' => 'Category C',
                'description' => '',
            ],
        ]);
    }

    /**
     * Test the subServiceCategories method of ServiceCategoryController.
     *
     * @return void
     */
    public function testSubServiceCategories()
    {
        // Create some test data in the database
        factory(ServiceCategory::class)->create([
            'description' => 'Description',
        ], 3);

        // Make a POST request to the subServiceCategories method with search parameter
        $user = factory(User::class)->create();
        $response = $this->post('/api/sub-service-categories', ['search' => 'Description'], [
            'Authorization' => 'Bearer ' . $user->api_token,
        ]);

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the response contains the correct data from the database
        $response->assertJson([
            [
                'description' => 'Description',
            ],
        ]);
    }
}
