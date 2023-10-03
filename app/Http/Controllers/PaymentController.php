<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Enrolment;
use App\Institution;
use App\InstructorCourse;
use App\Payment;
use App\Traits\PaymentsTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    use PaymentsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $this->updatedLatestCustomerPaymentStatus($user);

        return DB::table('payments')
            ->join('carts', 'payments.cart_id', '=', 'carts.cart_id')
            ->join('customers', 'carts.customer_id', '=', 'customers.customer_id')
            ->join('users', 'customers.user_id', '=', 'users.user_id')
            ->select('payments.*', 'carts.order_id')
            ->where('users.user_id', $user->user_id)
            ->get();
    }

    public function pay(Request $request)
    {
        $payments = [];
        /*$payment = Payment::select("*")
            ->where('cart_id', request('cart_id'))
            ->where(function($query){
                $query->where('status', 'ACCEPTED')
                    ->orWhere('status', 'PENDING');
            })
            ->latest('created_at')
            ->first();*/

        $payment = DB::table('payments')
            ->join('carts', 'carts.cart_id', '=', 'payments.cart_id')
            ->join('customers', 'customers.customer_id', '=', 'carts.customer_id')
            ->select('payments.*')
            ->whereRaw('(payments.status = ? OR payments.status = ?) AND customers.customer_id = ?', ['ACCEPTED', 'PENDING', request('customer_id')])
            ->latest('created_at')
            ->get()
            ->first();

        Log::info('status_response', [
            '$payment' => $payment
        ]);

        if ($payment) {
            $payment = Payment::find($payment->payment_id);
            $time_created = Carbon::createFromFormat('Y-m-d H:i:s', $payment->created_at);
            $now = Carbon::now();
            $wait_time = 0 - $now->diffInRealMinutes($time_created);

            if ($wait_time <= 0) {
                //Check status
                $curl = curl_init();
                $pending_payment_token = hash('sha512', $payment->payment_id . env("MOMO_PAYMENT_API_USERNAME") . env("MOMO_PAYMENT_API_PASSWORD"));
                $clientid = env("MOMO_PAYMENT_API_USERNAME");
                curl_setopt_array($curl, array(
                    CURLOPT_URL => env("MOMO_PAYMENT_API_URL"),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{\"header\": {\"clientid\": \"{$clientid}\",\"countrycode\": \"{$payment->country_code}\",\"requestid\": \"{$payment->payment_id}\",\"token\": \"{$pending_payment_token}\"},\"requesttype\": \"DEBIT\",\"paymentref\": \"{$payment->payment_ref}\"}",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json"
                    ),
                ));

                $status_response = curl_exec($curl);

                Log::info('status_response', [
                    'status_response' => $status_response
                ]);

                curl_close($curl);
                //end status check
                if ($status_response == "{}") {
                    return response()->json(array(
                        'wait_time' => $wait_time,
                    ));
                }
                $decoded_response = json_decode($status_response);

                if ($decoded_response->header->message == 'SUCCESS' && $decoded_response->header->status == '000') {
                    $aupdate_arr = ['status' => $decoded_response->transactionstatus];
                    if (isset($decoded_response->transactionstatusreason)) {
                        $aupdate_arr = $aupdate_arr + ['transactionstatusreason' => $decoded_response->transactionstatusreason];
                    }
                    $payment->update(
                        $aupdate_arr
                    );
                }


                if ($payment->status == 'ACCEPTED' || $payment->status == 'PENDING') {
                    return response()->json(array(
                        'wait_time' => $wait_time,
                    ));
                }
                // pay
                $payments[] = Payment::find($payment->payment_id);
                $payments[] = $this->initiatePayment($request);

                return response()->json(array(
                    'payments' => $payments,
                ));
            }
            return response()->json(array(
                'wait_time' => $wait_time,
            ));
        }

        // pay
        $payments[] = $this->initiatePayment($request);

        return response()->json(array(
            'payments' => $payments,
        ));
    }

    public function callback(Request $request)
    {
        Log::info('callback_request_params', [
            'callback_request_params' => $request
        ]);

        $payment = Payment::where('payment_ref', request('paymentref'))
            ->where('external_reference_no', '=', request('externalrefno'))
            ->first();

        if ($payment) {
            $payment->update(
                [
                    'payment_ref' => request('paymentref'),
                    'external_reference_no' => request('externalrefno'),
                    'status' => request('status')
                ]
            );

            if (request('status') == 'FAILED') {
                //Check status
                $curl = curl_init();
                $payment_token = hash('sha512', $payment->payment_id . env("MOMO_PAYMENT_API_USERNAME") . env("MOMO_PAYMENT_API_PASSWORD"));
                $clientid = env("MOMO_PAYMENT_API_USERNAME");
                curl_setopt_array($curl, array(
                    CURLOPT_URL => env("MOMO_PAYMENT_API_URL"),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{\"header\": {\"clientid\": \"{$clientid}\",\"countrycode\": \"{$payment->country_code}\",\"requestid\": \"{$payment->payment_id}\",\"token\": \"{$payment_token}\"},\"requesttype\": \"DEBIT\",\"paymentref\": \"{$payment->payment_ref}\"}",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json"
                    ),
                ));

                $status_response = curl_exec($curl);

                Log::info('status_response', [
                    'status_response' => $status_response
                ]);

                curl_close($curl);
                //end status check
                $decoded_response = json_decode($status_response);

                if ($decoded_response->header->message == 'SUCCESS' && $decoded_response->header->status == '000') {
                    $aupdate_arr = ['status' => $decoded_response->transactionstatus];
                    if (isset($decoded_response->transactionstatusreason)) {
                        $aupdate_arr = $aupdate_arr + ['transaction_status_reason' => $decoded_response->transactionstatusreason];
                    }
                    $payment->update(
                        $aupdate_arr
                    );
                }
            }
        }

        Log::info('payment_not_found', [
            'payment_not_found' => 'payment not found'
        ]);

        return response()->noContent();
    }

    /**
     * @param Request $request
     * @param $header
     * @param $body
     * @return mixed
     */
    public function initiatePayment(Request $request)
    {
        $payment_id = Str::uuid();
        $arr = [
            'payment_id' => $payment_id,
            'msisdn' => request('msisdn'),
            'country_code' => request('country_code'),
            'network' => request('network'),
            'currency' => request('currency'),
            'amount' => request('amount'),
        ];
        Payment::forceCreate(
            $arr + ['cart_id' => request('cart_id')]
        );
        $payment = Payment::find($payment_id);

        $header['clientid'] = env("MOMO_PAYMENT_API_USERNAME");
        $header['countrycode'] = $payment->country_code;
        $header['requestid'] = $payment_id;
        $header['token'] = hash('sha512', $payment_id . env("MOMO_PAYMENT_API_USERNAME") . env("MOMO_PAYMENT_API_PASSWORD"));

        $body['header'] = $header;
        $body['msisdn'] = $payment->msisdn;
        $body['network'] = $payment->network;
        $body['description'] = "Ekumfi";
        $body['amount'] = (double)$payment->amount;
        $body['currency'] = $payment->currency;

        $curl = curl_init();

        $clientid = env("MOMO_PAYMENT_API_USERNAME");
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://95.216.23.17:8080/momogateway/services/external/debitcustomer",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"header\": {\"clientid\": \"{$clientid}\",\"countrycode\": \"{$header['countrycode']}\",\"requestid\": \"{$header['requestid']}\",\"token\": \"{$header['token']}\"},\"msisdn\": \"{$body['msisdn']}\",\"network\": \"{$body['network']}\",\"description\": \"{$body['description']}\",\"amount\": {$body['amount']},\"currency\": \"{$body['currency']}\"}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        Log::info('debit_response', [
            'debit_response' => $response
        ]);

        curl_close($curl);
        $decoded_response = json_decode($response);
        //        {"debit_response":"{\"header\":{\"message\":\"Internal Error\",\"status\":\"100\"}}"}
        if (strtolower($decoded_response->header->message) == 'internal error') {
            return response()->json(array(
                'internal_error' => true
            ));
        }
        $payment->update(
            [
                'message' => $decoded_response->header->message,
                'status' => $decoded_response->header->status,
                'external_reference_no' => $decoded_response->externalreferenceno,
                'payment_ref' => $decoded_response->paymentref,
            ]
        );
        return Payment::find($payment->payment_id);
    }
}
