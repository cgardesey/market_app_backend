<?php

namespace App\Http\Controllers;

use App\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Responsey
     */
    public function index()
    {
        return ServiceCategory::orderBy('title', 'ASC')->where('description', "")->get();
    }

    public function subServiceCategories(Request $request)
    {
        return ServiceCategory::orderBy('title', 'ASC')->where('description', request('search'))->get();
    }

}
