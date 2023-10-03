<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Enrolment;
use App\Institution;
use App\InstitutionFee;
use App\Instructorbanner;
use App\Payment;
use App\SubscriptionChangeRequest;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Banner::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bannerid = Str::uuid();
        banner::forceCreate(
            ['banner_id' => $bannerid] +
            $request->all());

        $banner = banner::where('banner_id', $bannerid)->first();

        return response()->json($banner);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\banner $banner
     * @return \Illuminate\Http\Response
     */
    public function show(banner $banner)
    {
        return $banner;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\banner $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, banner $banner)
    {
        $banner->update($request->all());

        $updated_banner = Banner::where('banner_id', $banner->banner_id)->first();

        return response()->json($updated_banner);
    }
}
