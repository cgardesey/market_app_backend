<?php

namespace Tests\Unit\Controllers;

use App\Product;
use App\ProductCategory;
use App\ProductImage;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test store method of ProductController.
     *
     * @return void
     */
    public function testStore()
    {
        $requestData = factory(Product::class)->make()->toArray();

        $user = factory(User::class)->create();
        $response = $this->post('/api/products', $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => $requestData['name'],
            'description' => $requestData['description']
        ]);

        $this->assertDatabaseHas('products', [
            'name' => $requestData['name'],
            'description' => $requestData['description']
        ]);
    }

    /**
     * Test show method of ProductController.
     *
     * @return void
     */
    public function testShow()
    {/**/
        $product = factory(Product::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/products/' . $product->product_id, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'product_id' => $product->product_id,
            'name' => $product->name,
            'description' => $product->description
        ]);
    }

    /**
     * Test update method of ProductController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $product = factory(Product::class)->create();
        $product->makeVisible('product_id');

        $requestData = factory(Product::class)->make()->toArray();

        $user = factory(User::class)->create();

        $response = $this->patch('/api/products/' . $product->product_id, $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => $requestData['name'],
            'description' => $requestData['description']
        ]);

        $product->refresh();

        $this->assertDatabaseHas('products', [
            'name' => $requestData['name'],
            'description' => $requestData['description']
        ]);
    }

    public function testDestroy()
    {
        $product = factory(Product::class)->create();

        $user = factory(User::class)->create();
        $response = $this->delete('/api/products/' . $product->product_id, [], [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        // Assert the response status is 200 OK
        $response->assertStatus(200);

        // Assert the JSON response contains the correct status value
        $response->assertJson([
            'status' => true
        ]);

        // Assert the product is deleted from the database
        $this->assertDeleted($product);
    }

    public function testFilteredProducts()
    {
        $user = factory(User::class)->create();

        factory(ProductCategory::class)->create(['title' => 'Home Care >> Cleaning']);
        factory(ProductImage::class, 3)->create([
            'product_id' => factory(Product::class)->create(['product_category' => 'Home Care >> Cleaning'])->product_id,
            'featured_image' => 1
        ]);

        $response = $this->post('/api/filtered-products', ['product_category' => 'Home Care >> Cleaning'],
            [
                'Authorization' => 'Bearer ' . $user->api_token,
            ]);

        $response->assertStatus(200);

        // Check if the response contains the expected data
        $response->assertJsonCount(3);
    }

    public function testSubProducts()
    {
        $user = factory(User::class)->create();

        factory(ProductCategory::class)->create(['title' => 'Home Care >> Cleaning']);
        factory(ProductImage::class, 3)->create([
            'product_id' => factory(Product::class)->create(['product_category' => 'Home Care >> Cleaning'])->product_id,
            'featured_image' => 1
        ]);

        $response = $this->post('/api/sub-products', ['product_category' => 'Home Care >> Cleaning'],
            [
                'Authorization' => 'Bearer ' . $user->api_token,
            ]);

        $response->assertStatus(200);

        // Check if the response contains the expected data
        $response->assertJsonCount(3);
    }
}
