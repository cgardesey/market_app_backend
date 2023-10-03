<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartProduct;
use App\Enrolment;
use App\Institution;
use App\InstitutionFee;
use App\InstructorCart;
use App\Payment;
use App\SubscriptionChangeRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function scopedCarts(Request $request)
    {
        $collection = DB::table('carts')
            ->join('providers', 'carts.provider_id', '=', 'providers.provider_id')
            ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
            ->select('carts.*', 'providers.longitude', 'providers.latitude', 'providers.provider_name', 'providers.title', 'providers.first_name', 'providers.last_name', 'providers.other_name', 'providers.verified', 'providers.profile_image_url', DB::raw("count(cart_products.id) as item_count"))
            ->groupBy('carts.id')
            ->where('carts.customer_id', 'LIKE', '%' . request('customer_id') . '%')
            ->get();
        Log::info('collection', [$collection]);
        return $collection;
    }

    public function scopedCustomerCarts(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $this->updatedLatestCustomerPaymentStatus($user);

        return DB::table('carts')
            ->leftJoin('payments', 'carts.cart_id', '=', 'payments.cart_id')
            ->join('providers', 'carts.provider_id', '=', 'providers.provider_id')
            ->join('customers', 'carts.customer_id', '=', 'customers.customer_id')
            ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
            ->select('carts.*', 'payments.status AS status', 'customers.longitude AS customer_longitude', 'customers.latitude AS customer_latitude', 'customers.name AS customer_name', 'customers.profile_image_url AS customer_profile_image_url', 'providers.longitude AS provider_longitude', 'providers.latitude AS provider_latitude', 'providers.provider_name', 'providers.title', 'providers.first_name', 'providers.last_name', 'providers.other_name', 'providers.verified', 'providers.shop_image_url', DB::raw("count(cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.cart_id)) OR payments.id IS NULL) AND carts.customer_id = ?', [request('customer_id')])
            ->groupBy('carts.id')
            ->get();
    }

    public function scopedProviderCarts(Request $request)
    {
        return DB::table('carts')
            ->leftJoin('payments', 'carts.cart_id', '=', 'payments.cart_id')
            ->join('providers', 'carts.provider_id', '=', 'providers.provider_id')
            ->join('customers', 'carts.customer_id', '=', 'customers.customer_id')
            ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
            ->select('carts.*', 'payments.status AS status', 'customers.longitude AS customer_longitude', 'customers.latitude AS customer_latitude', 'customers.name AS customer_name', 'customers.profile_image_url AS customer_profile_image_url', 'providers.longitude AS provider_longitude', 'providers.latitude AS provider_latitude', 'providers.provider_name', 'providers.title', 'providers.first_name', 'providers.last_name', 'providers.other_name', 'providers.verified', 'providers.shop_image_url', DB::raw("count(cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.cart_id)) OR payments.id IS NULL) AND carts.provider_id = ?', [request('provider_id')])
            ->groupBy('carts.id')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        Log::info('request_data', [
            $request
        ]);
        $cart = Cart::where('provider_id', request("provider_id"))
            ->where('customer_id', request("customer_id"))
            ->latest('created_at')
            ->first();

        if ($cart == null) {
            $cart_id = Str::uuid();
            Cart::forceCreate(
                [
                    'cart_id' => $cart_id,
                    'order_id' => random_int(100000000000, 999999999999),
                    'provider_id' => request("provider_id"),
                    'customer_id' => request("customer_id"),
                ]
            );
            $cart = Cart::where('cart_id', $cart_id)->first();
        }

        $cart_product = CartProduct::where('cart_id', $cart->cart_id)
            ->where('product_id', request("product_id"))
            ->first();

        if ($cart_product) {
            return Response::json(array(
                'success' => false,
                'cart' => $cart
            ));
        }

        CartProduct::forceCreate(
            [
                'cart_product_id' => Str::uuid(),
                'cart_id' => $cart->cart_id,
                'product_id' => request("product_id"),
                'quantity' => request("quantity"),
                'price' => request("price"),
            ]
        );

        return Response::json(array(
            'success' => true,
            'cart' => $cart
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        return $cart;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        $cart->update($request->all());

        $updated_cart = Cart::where('cart_id', $cart->cart_id)->first();

        return response()->json($updated_cart);
    }
}
