<?php

namespace Tests\Unit;

use App\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $productImage = new ProductImage();
        $this->assertEquals('product_images', $productImage->getTable());
    }

    /** @test */
    public function it_uses_productImage_id_as_the_primary_key()
    {
        $productImage = new ProductImage();

        $this->assertEquals('product_image_id', $productImage->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $productImage = new ProductImage();

        $this->assertFalse($productImage->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_image()
    {
        $productImage = new ProductImage();
        $this->assertEquals('string', $productImage->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $productImage = new ProductImage();
        $hidden = ['id'];
        $this->assertEquals($hidden, $productImage->getHidden());
    }

    /** @test */
    public function it_returns_productImage_id_for_route_key()
    {
        $productImage = new ProductImage();

        $this->assertEquals('product_image_id', $productImage->getRouteKeyName());
    }
}
