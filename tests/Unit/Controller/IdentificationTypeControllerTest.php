<?php

namespace Tests\Unit\Controllers;

use App\IdentificationType;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdentificationTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method of IdentificationTypeController.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = factory(User::class)->create();

        $identificationTypes = factory(IdentificationType::class, 3)->create();

        $response = $this->get('/api/identification-types', [
            'Authorization' => 'Bearer ' . $user->api_token,
        ]);

        $response->assertStatus(200);


        $response->assertJsonFragment([
            'identification_type_id' => $identificationTypes[0]->identification_type_id,
            'name' => $identificationTypes[0]->name,
            'description' => $identificationTypes[0]->description
        ]);
    }

    /**
     * Test store method of IdentificationTypeController.
     *
     * @return void
     */
    public function testStore()
    {
        $requestData = [
            'name' => "Odit quas aperiam placeat nemo.",
            'description' => "Velit rerum aperiam quod. Eligendi fugit voluptate quia expedita sint. Nihil est dolor distinctio ipsum. Praesentium magni ex dolore ut."
        ];

        $user = factory(User::class)->create();
        $response = $this->post('/api/identification-types', $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment($requestData);

        $this->assertDatabaseHas('identification_types', $requestData);
    }

    /**
     * Test show method of IdentificationTypeController.
     *
     * @return void
     */
    public function testShow()
    {
        $identificationType = factory(IdentificationType::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/identification-types/' . $identificationType->identification_type_id, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'identification_type_id' => $identificationType->identification_type_id,
            'name' => $identificationType->name,
            'description' => $identificationType->description
        ]);
    }

    /**
     * Test update method of IdentificationTypeController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $identification_type = factory(IdentificationType::class)->create();

        $requestData = factory(IdentificationType::class)->make()->toArray();

        $user = factory(User::class)->create();

        $response = $this->patch('/api/identification-types/' . $identification_type->identification_type_id, [
            'name' => $requestData['name'],
            'description' => $requestData['description']
        ], [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => $requestData['name'],
            'description' => $requestData['description']
        ]);

        $identification_type->refresh();

        $this->assertDatabaseHas('identification_types', [
            'name' => $requestData['name'],
            'description' => $requestData['description']
        ]);
    }
}
