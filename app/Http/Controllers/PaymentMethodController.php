<?php

namespace App\Http\Controllers;

use App\Method;
use App\Enrolment;
use App\PaymentMethod;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return PaymentMethod::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payment_method_id = Str::uuid();
        PaymentMethod::forceCreate(
            ['payment_method_id' => $payment_method_id] +
            $request->all());

        $paymentmethod = PaymentMethod::where('payment_method_id', $payment_method_id)->first();

        return response()->json($paymentmethod);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PaymentMethod  $paymentmethod
     * @return \Illuminate\Http\Response
     */
    public function show($payment_method_id)
    {
        $paymentmethod = PaymentMethod::where('payment_method_id', $payment_method_id)->first();

        return response()->json($paymentmethod);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PaymentMethod  $payment_method
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentMethod $payment_method)
    {
        $payment_method->update($request->all());

        $updated_paymentmethod = PaymentMethod::where('payment_method_id', $payment_method->payment_method_id)->first();

        return response()->json($updated_paymentmethod);
    }
}
