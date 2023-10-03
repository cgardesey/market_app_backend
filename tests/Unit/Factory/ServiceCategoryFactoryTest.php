<?php

namespace Tests\Unit\Factories;

use App\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceCategoryFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_creates_a_service_category_with_valid_data()
    {
        $serviceCategory = factory(ServiceCategory::class)->create();

        $this->assertInstanceOf(ServiceCategory::class, $serviceCategory);
        $this->assertDatabaseHas('service_categories', [
            'service_category_id' => $serviceCategory->service_category_id,
            'title' => $serviceCategory->title,
            'description' => $serviceCategory->description,
        ]);
    }
}
