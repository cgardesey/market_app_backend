<?php

namespace Tests\Unit;

use App\Product;
use App\ProductImage;
use App\ProvisionTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $product = new Product();
        $this->assertEquals('products', $product->getTable());
    }

    /** @test */
    public function it_uses_product_id_as_the_primary_key()
    {
        $product = new Product();

        $this->assertEquals('product_id', $product->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $product = new Product();

        $this->assertFalse($product->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $product = new Product();
        $this->assertEquals('string', $product->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $product = new Product();
        $hidden = ['id'];
        $this->assertEquals($hidden, $product->getHidden());
    }

    /** @test */
    public function it_returns_product_id_for_route_key()
    {
        $product = new Product();

        $this->assertEquals('product_id', $product->getRouteKeyName());
    }

    public function test_product_model_provision_tags_relationship()
    {
        $product = new Product();
        $relation = $product->provisionTags();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphToMany::class, $relation);
//        $this->assertEquals(ProvisionTag::class, $relation->getRelated());
    }

    public function test_product_model_product_images_relationship()
    {
        $product = new Product();
        $relation = $product->productImages();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relation);
//        $this->assertEquals(ProductImage::class, $relation->getRelated());
        $this->assertEquals('product_image_id', $relation->getForeignKeyName());
        $this->assertEquals('product_image_id', $relation->getLocalKeyName());
    }
}
