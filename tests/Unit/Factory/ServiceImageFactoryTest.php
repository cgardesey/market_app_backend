<?php

namespace Tests\Unit\Factories;

use App\Service;
use App\ServiceImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceImageFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_creates_a_service_image_with_valid_data()
    {
        $serviceImage = factory(ServiceImage::class)->create();

        $this->assertInstanceOf(ServiceImage::class, $serviceImage);
        $this->assertDatabaseHas('service_images', [
            'service_image_id' => $serviceImage->service_image_id,
            'url' => $serviceImage->url,
            'name' => $serviceImage->name,
            'service_id' => $serviceImage->service_id,
        ]);
    }
}
