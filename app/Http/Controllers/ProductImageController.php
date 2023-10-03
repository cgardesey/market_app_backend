<?php

namespace App\Http\Controllers;

use App\Image;
use App\Enrolment;
use App\Provider;
use App\ProductImage;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ProductImageController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function scopedProductImages(Request $request)
    {
        return ProductImage::where('product_id', request('product_id'))
            ->where('product_id', request('product_id'))
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_image_id = Str::uuid();
        $attributes = [];
        if($request->hasFile("image_file")) {
            // Define folder path
            $folder = '/uploads/product-images/';// Get image file
            $image = $request->file("image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $product_image_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $product_image_id);

            $attributes = $attributes + ['url' => asset('storage/app') . "$filePath"];
        }
        $attributes = $attributes + [
                'product_image_id' => $product_image_id,
                'product_id' => request('product_id'),
                'featured_image' => request('featured_image'),
            ];

        ProductImage::forceCreate(
            $attributes
        );

        $product_image = ProductImage::where('product_image_id', $product_image_id)->first();

        return $product_image;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductImage  $productimage
     * @return \Illuminate\Http\Response
     */
    public function show($product_image_id)
    {
        $productimage = ProductImage::where('product_image_id', $product_image_id)->first();

        return response()->json($productimage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductImage  $productimage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductImage $product_image)
    {
        DB::table('product_images')
            ->where('product_id', $product_image->product_id)
            ->update(['featured_image' => 0]);

        DB::table('product_images')
            ->where('product_image_id', $product_image->product_image_id)
            ->update(['featured_image' => 1]);

        return ProductImage::where('product_id', $product_image->product_id)->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductImage  $productimage
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductImage $product_image)
    {
        $status = $product_image->delete();
        return Response::json(array(
            'status' => $status
        ));
    }
}
