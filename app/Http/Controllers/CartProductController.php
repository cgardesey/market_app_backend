<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Cart;
use App\CartProduct;
use App\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CartProductController extends Controller
{

    public function scopedCartProducts(Request $request)
    {
        return DB::table('cart_products')
            ->join('products', 'products.product_id', '=', 'cart_products.product_id')
            ->join('product_images', 'products.product_id', '=', 'product_images.product_id')
            ->select('cart_products.*', 'products.product_category', 'product_images.url', 'product_images.url', 'products.unit_quantity', 'products.quantity_available', 'products.quantity_available', 'products.unit_price')
            ->where('cart_products.cart_id', $request->has("order_id") ? Cart::where('order_id', request('order_id'))->first()->cart_id : request('cart_id'))
            ->where('product_images.featured_image', 1)
            ->get();
    }

    public function scopedCartTotal(Request $request)
    {
        return response()->json(array(
            'cart_total' => DB::table('cart_products')
                ->select(DB::raw("sum(cart_products.price) as total_amount"))
                ->where('cart_products.cart_id', request('cart_id'))
                ->first()
                ->total_amount,
            'provider' => Provider::find(Cart::find(request('cart_id'))->provider_id)
        ));
    }

    public function scopedCartTotalCount(Request $request)
    {
        return response()->json(array(
            'cart_total' => DB::table('cart_products')
                ->select(DB::raw("sum(cart_products.price) as total_amount"))
                ->where('cart_products.cart_id', request('cart_id'))
                ->first()
                ->total_amount,
            'item_count' => CartProduct::where('cart_id', request('cart_id'))->get()->count(),
            'provider' => Provider::find(Cart::find(request('cart_id'))->provider_id)
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CartProduct  $cartProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CartProduct $cartProduct)
    {
        $cartProduct->update($request->all());

        $updated_cart_product = CartProduct::where('cart_product_id', $cartProduct->cart_product_id)->first();

        return response()->json($updated_cart_product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CartProduct  $cartProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(CartProduct $cartProduct)
    {
        $status = $cartProduct->delete();
        return Response::json(array(
            'status' => $status
        ));
    }
}
