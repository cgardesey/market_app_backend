<?php

namespace Tests\Unit;

use App\Service;
use App\ServiceImage;
use App\ProvisionTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $service = new Service();
        $this->assertEquals('services', $service->getTable());
    }

    /** @test */
    public function it_uses_service_id_as_the_primary_key()
    {
        $service = new Service();

        $this->assertEquals('service_id', $service->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $service = new Service();

        $this->assertFalse($service->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $service = new Service();
        $this->assertEquals('string', $service->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $service = new Service();
        $hidden = ['id'];
        $this->assertEquals($hidden, $service->getHidden());
    }

    /** @test */
    public function it_returns_service_id_for_route_key()
    {
        $service = new Service();

        $this->assertEquals('service_id', $service->getRouteKeyName());
    }

    public function test_service_model_provision_tags_relationship()
    {
        $service = new Service();
        $relation = $service->provisionTags();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphToMany::class, $relation);
//        $this->assertEquals(ProvisionTag::class, $relation->getRelated());
    }

    public function test_service_model_service_images_relationship()
    {
        $service = new Service();
        $relation = $service->serviceImages();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relation);
//        $this->assertEquals(ServiceImage::class, $relation->getRelated());
        $this->assertEquals('service_image_id', $relation->getForeignKeyName());
        $this->assertEquals('service_image_id', $relation->getLocalKeyName());
    }
}
