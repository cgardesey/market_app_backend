<?php

namespace Tests\Unit;

use App\ServiceImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceImageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $serviceImage = new ServiceImage();
        $this->assertEquals('service_images', $serviceImage->getTable());
    }

    /** @test */
    public function it_uses_serviceImage_id_as_the_primary_key()
    {
        $serviceImage = new ServiceImage();

        $this->assertEquals('service_image_id', $serviceImage->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $serviceImage = new ServiceImage();

        $this->assertFalse($serviceImage->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_image()
    {
        $serviceImage = new ServiceImage();
        $this->assertEquals('string', $serviceImage->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $serviceImage = new ServiceImage();
        $hidden = ['id'];
        $this->assertEquals($hidden, $serviceImage->getHidden());
    }

    /** @test */
    public function it_returns_serviceImage_id_for_route_key()
    {
        $serviceImage = new ServiceImage();

        $this->assertEquals('service_image_id', $serviceImage->getRouteKeyName());
    }
}
