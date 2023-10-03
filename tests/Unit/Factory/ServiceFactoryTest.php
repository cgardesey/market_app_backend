<?php

namespace Tests\Unit\Factories;

use App\Provider;
use App\Service;
use App\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_creates_a_service_with_valid_data()
    {
        $service = factory(Service::class)->create();

        $this->assertInstanceOf(Service::class, $service);
        $this->assertDatabaseHas('services', [
            'service_id' => $service->service_id,
            'service_category' => $service->service_category,
            'name' => $service->name,
            'description' => $service->description,
            'provider_id' => $service->provider_id,
        ]);
    }
}
