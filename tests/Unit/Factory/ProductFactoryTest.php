<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Product;
use App\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_product()
    {
        $product = factory(Product::class)->create();

        $this->assertInstanceOf(Product::class, $product);
        $this->assertDatabaseHas('products', [
            'product_id' => $product->product_id,
            'product_category' => $product->product_category,
            'name' => $product->name,
            'description' => $product->description,
            'unit_quantity' => $product->unit_quantity,
            'quantity_available' => $product->quantity_available,
            'unit_price' => $product->unit_price,
            'rating' => $product->rating,
            'total_rating' => $product->total_rating,
            'provider_id' => $product->provider_id,
        ]);
    }
}
