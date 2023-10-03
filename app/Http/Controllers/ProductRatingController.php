<?php

namespace App\Http\Controllers;

use App\ProductRating;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductRatingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_rating_id = Str::uuid();
        ProductRating::forceCreate(
            ['product_rating_id' => $product_rating_id] +
            $request->all());

        return  ProductRating::where('product_rating_id', $product_rating_id)->first();
    }
}
