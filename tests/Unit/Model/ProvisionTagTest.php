<?php

namespace Tests\Unit;

use App\Product;
use App\ProvisionTag;
use App\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProvisionTagTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $provisionTag = new ProvisionTag();
        $this->assertEquals('provision_tags', $provisionTag->getTable());
    }

    /** @test */
    public function it_uses_provisionTag_id_as_the_primary_key()
    {
        $provisionTag = new ProvisionTag();

        $this->assertEquals('provision_tag_id', $provisionTag->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $provisionTag = new ProvisionTag();

        $this->assertFalse($provisionTag->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_tag()
    {
        $provisionTag = new ProvisionTag();
        $this->assertEquals('string', $provisionTag->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $provisionTag = new ProvisionTag();
        $hidden = ['id'];
        $this->assertEquals($hidden, $provisionTag->getHidden());
    }

    /** @test */
    public function it_returns_provisionTag_id_for_route_key()
    {
        $provisionTag = new ProvisionTag();

        $this->assertEquals('provision_tag_id', $provisionTag->getRouteKeyName());
    }

    /** @test */
    public function it_has_relations_with_products_and_services()
    {
        // Create a ProvisionTag instance
        $provisionTag = ProvisionTag::forceCreate([
            'provision_tag_id' => (string) Str::uuid(),
        ]);

        // Create related Product and Service instances
        $product = Product::forceCreate([
            'product_id' => (string) Str::uuid()
        ]);

        $service = Service::forceCreate([
            'service_id' => (string) Str::uuid()
        ]);

        // Attach the Product and Service instances to the ProvisionTag
        $provisionTag->provisions()->attach($product->product_id);
        $provisionTag->services()->attach($service->service_id);

        // Assert the relations
        $this->assertCount(1, $provisionTag->provisions);
        $this->assertCount(1, $provisionTag->services);
        $this->assertInstanceOf(Product::class, $provisionTag->provisions->first());
        $this->assertInstanceOf(Service::class, $provisionTag->services->first());
    }
}
