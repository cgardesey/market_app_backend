<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\ProductImage;
use App\Product;

class ProductFactoryImageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_create_a_product_image()
    {
        $product = factory(Product::class)->create();
        $productImage = factory(ProductImage::class)->create(['product_id' => $product->product_id]);

        $this->assertDatabaseHas('product_images', [
            'product_image_id' => $productImage->product_image_id,
            'url' => $productImage->url,
            'name' => $productImage->name,
            'description' => $productImage->description,
            'featured_image' => $productImage->featured_image,
            'product_id' => $product->product_id,
        ]);
    }
}
