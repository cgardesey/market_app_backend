<?php

namespace Tests\Unit;

use App\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $serviceCategory = new ServiceCategory();
        $this->assertEquals('service_categories', $serviceCategory->getTable());
    }

    /** @test */
    public function it_uses_serviceCategory_id_as_the_primary_key()
    {
        $serviceCategory = new ServiceCategory();

        $this->assertEquals('service_category_id', $serviceCategory->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $serviceCategory = new ServiceCategory();

        $this->assertFalse($serviceCategory->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_category()
    {
        $serviceCategory = new ServiceCategory();
        $this->assertEquals('string', $serviceCategory->getKeyType());
    }


    /** @test */
    public function it_returns_serviceCategory_id_for_route_key()
    {
        $serviceCategory = new ServiceCategory();

        $this->assertEquals('service_category_id', $serviceCategory->getRouteKeyName());
    }
}
