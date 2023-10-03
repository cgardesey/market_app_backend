<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\ProductRating;
use App\Product;

class ProductRatingFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_create_a_product_rating()
    {
        $product = factory(Product::class)->create();
        $productRating = factory(ProductRating::class)->create(['product_id' => $product->product_id]);

        $this->assertDatabaseHas('product_ratings', [
            'product_rating_id' => $productRating->product_rating_id,
            'product_id' => $product->product_id,
            'one_star' => $productRating->one_star,
            'two_star' => $productRating->two_star,
            'three_star' => $productRating->three_star,
            'four_star' => $productRating->four_star,
            'five_star' => $productRating->five_star,
            'review' => $productRating->review,
        ]);
    }
}
