<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\ProductCategory;

class ProductCategoryFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_create_a_product_category()
    {
        $productCategory = factory(ProductCategory::class)->create();

        $this->assertDatabaseHas('product_categories', [
            'product_category_id' => $productCategory->product_category_id,
            'title' => $productCategory->title,
            'description' => $productCategory->description,
            'url' => $productCategory->url,
            'tag' => $productCategory->tag,
        ]);
    }
}
