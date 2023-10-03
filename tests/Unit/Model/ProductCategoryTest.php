<?php

namespace Tests\Unit;

use App\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $productCategory = new ProductCategory();
        $this->assertEquals('product_categories', $productCategory->getTable());
    }

    /** @test */
    public function it_uses_productCategory_id_as_the_primary_key()
    {
        $productCategory = new ProductCategory();

        $this->assertEquals('product_category_id', $productCategory->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $productCategory = new ProductCategory();

        $this->assertFalse($productCategory->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_category()
    {
        $productCategory = new ProductCategory();
        $this->assertEquals('string', $productCategory->getKeyType());
    }

    /** @test */
    public function it_has_guarded_attributes()
    {
        $productCategory = new ProductCategory();
        $hidden = ['id', 'product_category_id'];
        $this->assertEquals($hidden, $productCategory->getGuarded());
    }

    /** @test */
    public function it_returns_productCategory_id_for_route_key()
    {
        $productCategory = new ProductCategory();

        $this->assertEquals('product_category_id', $productCategory->getRouteKeyName());
    }
}
