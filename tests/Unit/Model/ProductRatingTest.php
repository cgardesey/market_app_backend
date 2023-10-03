<?php

namespace Tests\Unit;

use App\ProductRating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRatingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $productRating = new ProductRating();
        $this->assertEquals('product_ratings', $productRating->getTable());
    }

    /** @test */
    public function it_uses_productRating_id_as_the_primary_key()
    {
        $productRating = new ProductRating();

        $this->assertEquals('product_rating_id', $productRating->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $productRating = new ProductRating();

        $this->assertFalse($productRating->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_rating()
    {
        $productRating = new ProductRating();
        $this->assertEquals('string', $productRating->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $productRating = new ProductRating();
        $hidden = ['id'];
        $this->assertEquals($hidden, $productRating->getHidden());
    }

    /** @test */
    public function it_returns_productRating_id_for_route_key()
    {
        $productRating = new ProductRating();

        $this->assertEquals('product_rating_id', $productRating->getRouteKeyName());
    }
}
