<?php

namespace App\Http\Controllers;

use App\Type;
use App\Enrolment;
use App\IdentificationType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class IdentificationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return IdentificationType::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $identification_type_id = Str::uuid();
        IdentificationType::forceCreate(
            ['identification_type_id' => $identification_type_id] +
            $request->all());

        $identificationtype = IdentificationType::where('identification_type_id', $identification_type_id)->first();

        return response()->json($identificationtype);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\IdentificationType  $identificationtype
     * @return \Illuminate\Http\Response
     */
    public function show($identification_type_id)
    {
        $identificationtype = IdentificationType::where('identification_type_id', $identification_type_id)->first();

        return response()->json($identificationtype);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\IdentificationType  $identification_type
     * @return \Illuminate\Http\Response
     *
     */
    public function update(Request $request, IdentificationType $identification_type)
    {
        $identification_type->update($request->all());
        $updated_identificationtype = IdentificationType::where('identification_type_id', $identification_type->identification_type_id)->first();
        return response()->json($updated_identificationtype);
    }
}
