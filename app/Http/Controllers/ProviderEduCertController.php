<?php

namespace App\Http\Controllers;

use App\ProviderEduCert;
use App\Enrolment;
use App\Institution;
use App\InstitutionFee;
use App\InstructorproviderEduCert;
use App\Payment;
use App\SubscriptionChangeRequest;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProviderEduCertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ProviderEduCert::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $provider_edu_cert_id = Str::uuid();
        ProviderEduCert::forceCreate(
            ['provider_edu_cert_id' => $provider_edu_cert_id] +
            $request->all());

        $providerEduCert = ProviderEduCert::where('provider_edu_cert_id', $provider_edu_cert_id)->first();

        return response()->json($providerEduCert);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\providerEduCert $providerEduCert
     * @return \Illuminate\Http\Response
     */
    public function show(providerEduCert $providerEduCert)
    {
        return $providerEduCert;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\providerEduCert $providerEduCert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, providerEduCert $providerEduCert)
    {
        $providerEduCert->update($request->all());

        $updated_providerEduCert = providerEduCert::where('provider_edu_cert_id', $providerEduCert->provider_edu_cert_id)->first();

        return response()->json($updated_providerEduCert);
    }
}
