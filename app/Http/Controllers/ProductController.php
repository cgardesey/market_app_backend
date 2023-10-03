<?php

namespace App\Http\Controllers;

use App\Product;
use App\Enrolment;
use App\Institution;
use App\InstitutionFee;
use App\InstructorProduct;
use App\Payment;
use App\ProductCategory;
use App\ProductImage;
use App\SubscriptionChangeRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function subProducts(Request $request)
    {
        $ProductCategory = ProductCategory::where("title", request('product_category'))->first();
        if ($ProductCategory) {
            $ProductCategory->product_category_id;
            return DB::table('products')
                ->join('providers', 'products.provider_id', '=', 'providers.provider_id')
                ->join('product_images', 'products.product_id', '=', 'product_images.product_id')
                ->select('products.*', 'providers.longitude', 'providers.latitude', 'providers.provider_name', 'providers.title', 'providers.first_name', 'providers.last_name', 'providers.other_name', 'providers.verified', 'providers.availability', 'product_images.url')
                ->where('products.product_category', 'LIKE', '%' . request('product_category') . '%')
                ->where('product_images.featured_image', 1)
                ->get();
        }
        return [];
    }

    public function filteredProducts(Request $request)
    {
        return DB::table('products')
            ->join('providers', 'products.provider_id', '=', 'providers.provider_id')
            ->join('product_images', 'products.product_id', '=', 'product_images.product_id')
            ->select('products.*', 'providers.longitude', 'providers.latitude', 'providers.provider_name', 'providers.title', 'providers.first_name', 'providers.last_name', 'providers.other_name', 'providers.verified', 'providers.availability', 'product_images.url')
            ->where('products.product_category', 'LIKE', '%' . request('search') . '%')
            ->where('product_images.featured_image', 1)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_id = Str::uuid();
        Product::forceCreate(
            ['product_id' => $product_id] +
            $request->all());

        $product = product::where('product_id', $product_id)->first();

        return response()->json($product);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        $updated_product = Product::where('product_id', $product->product_id)->first();

        return response()->json($updated_product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $status = $product->delete();
        return Response::json(array(
            'status' => $status
        ));
    }
}
