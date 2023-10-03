<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductCategory::orderBy('title', 'ASC')->where('description', "")->get();
    }

    public function subProductCategories(Request $request)
    {
        return ProductCategory::where('title', 'LIKE', '%' . request('search') . '%')->get();
    }
}
