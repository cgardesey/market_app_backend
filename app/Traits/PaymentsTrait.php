<?php
namespace App\Traits;
use App\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait PaymentsTrait
{
    public function updatedLatestCustomerPaymentStatus($user)
    {
        $payments = DB::table('payments')
            ->where(function ($query) {
                $query->where('payments.status', '=', 'ACCEPTED');
                $query->orWhere('payments.status', '=', 'PENDING');
            })
            ->join('carts', 'payments.cart_id', '=', 'carts.cart_id')
            ->join('customers', 'carts.customer_id', '=', 'customers.customer_id')
            ->join('users', 'customers.user_id', '=', 'users.user_id')
            ->select('payments.*', DB::raw("max(payments.id) as max_id"))
            ->groupBy('payments.id')
            ->where('users.user_id', $user->user_id)
            ->get();

        if (sizeof($payments) > 0) {
            $payment = $payments[0];
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
            if ($status_response != "{}") {
                $decoded_response = json_decode($status_response);

                if ($decoded_response->header->message == 'SUCCESS' && $decoded_response->header->status == '000') {
                    $aupdate_arr = ['status' => $decoded_response->transactionstatus];
                    if (isset($decoded_response->transactionstatusreason)) {
                        $aupdate_arr = $aupdate_arr + ['transactionstatusreason' => $decoded_response->transactionstatusreason];
                    }
                    Payment::find($payment->payment_id)->update(
                        $aupdate_arr
                    );
                }
            }
        }
    }

    public function updatedLatestProviderPaymentStatus($user)
    {
        $payments = DB::table('payments')
            ->where(function ($query) {
                $query->where('payments.status', '=', 'ACCEPTED');
                $query->orWhere('payments.status', '=', 'PENDING');
            })
            ->join('stock_carts', 'payments.stock_cart_id', '=', 'stock_carts.stock_cart_id')
            ->join('providers', 'stock_carts.provider_id', '=', 'providers.provider_id')
            ->join('users', 'providers.user_id', '=', 'users.user_id')
            ->select('payments.*', DB::raw("max(payments.id) as max_id"))
            ->groupBy('payments.id')
            ->where('users.user_id', $user->user_id)
            ->get();

        if (sizeof($payments) > 0) {
            $payment = $payments[0];
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
            if ($status_response != "{}") {
                $decoded_response = json_decode($status_response);

                if ($decoded_response->header->message == 'SUCCESS' && $decoded_response->header->status == '000') {
                    $aupdate_arr = ['status' => $decoded_response->transactionstatus];
                    if (isset($decoded_response->transactionstatusreason)) {
                        $aupdate_arr = $aupdate_arr + ['transactionstatusreason' => $decoded_response->transactionstatusreason];
                    }
                    Payment::find($payment->payment_id)->update(
                        $aupdate_arr
                    );
                }
            }
        }
    }
}
