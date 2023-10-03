<?php

namespace App\Http\Controllers;

use App\Banner;
use App\CartProduct;
use App\Chat;
use App\Customer;
use App\ProductCategory;
use App\Provider;
use App\RideHistory;
use App\RideStop;
use App\Service;
use App\ServiceCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class RideHistoryController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\RideHistory $rideHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RideHistory $rideHistory)
    {
        if ($request->has('arrived')) {
            $rideHistory->update(["start_time" => Carbon::now()]);

            $provider = Provider::find(request('provider_id'));

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://ghanapostgps.sperixlabs.org/get-address',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => "long={$provider->longitude}&lat={$provider->latitude}",
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $decoded_response = json_decode($response);

            if ($decoded_response->found) {
                $address = $decoded_response->data->Table[0]->Street;
            } else {
                $address = "Unknown Location";
            }


            RideStop::forceCreate([
                'ride_stop_id' => Str::uuid(),
                'latitude' => $provider->latitude,
                'longitude' => $provider->longitude,
                'address' => $address,
                'ride_history_id' => $rideHistory->ride_history_id,
            ]);
        } elseif ($request->has('destination_latitude')) {
            $destination_lat = request('destination_latitude');
            $destination_long = request('destination_longitude');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://ghanapostgps.sperixlabs.org/get-address',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => "long={$destination_lat}&lat={$destination_long}",
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $decoded_response = json_decode($response);

            if ($decoded_response->found) {
                $destination_address = $decoded_response->data->Table[0]->Street;
            } else {
                $destination_address = "Unknown Location";
            }

            $rideHistory->update([
                'destination_latitude' => $destination_lat,
                'destination_longitude' => $destination_long,
                'destination_address' => $destination_address,
            ]);
        } elseif (request('ride_cancelled') == 0) {
            $provider = Provider::find(
                Service::find($rideHistory->service_id)->provider_id
            );
            $customer = Customer::find($rideHistory->customer_id);

            $curl = curl_init();

            $FCM_API_KEY = env("FCM_API_KEY");
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\"data\":{\"type\":\"ride_request_accepted\",\"title\":\"Ride Request Accepted\",\"ride_history_id\":\"{$rideHistory->ride_history_id}\",\"pickup_lat\":\"{$rideHistory->pickup_latitude}\",\"pickup_long\":\"{$rideHistory->pickup_longitude}\",\"destination_lat\":\"{$rideHistory->destination_latitude}\",\"destination_long\":\"{$rideHistory->destination_longitude}\",\"provider_lat\":\"{$provider->latitude}\",\"provider_long\":\"{$provider->longitude}\",\"provider_name\":\"{$provider->first_name}\",\"provider_profile_image_url\":\"{$provider->profile_image_url}\",\"pickup_address\":\"{$rideHistory->pickup_address}\",\"destination_address\":\"{$rideHistory->destination_address}\",\"service_id\":\"{$rideHistory->service_id}\",\"provider_primary_contact\":\"{$provider->primary_contact}\",\"vehicle_type\":\"{$provider->vehicle_type}\",\"vehicle_registration_number\":\"{$provider->vehicle_registration_number}\",\"provider_confirmation_token\":\"{$provider->confirmation_token}\",\"customer_id\":\"{$customer->customer_id}\",\"provider_id\":\"{$provider->provider_id}\"},\"android\":{\"priority\":\"normal\"},\"registration_ids\":[\"{$customer->confirmation_token}\"]}",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: key={$FCM_API_KEY}"
                ),
            ));

            $response = curl_exec($curl);

            Log::info('status_response', [
                'notify_ride_request_accepted' => $response
            ]);

            curl_close($curl);
        } else {
            $rideHistory->update($request->all());
        }

        $updated_ride_history = RideHistory::where('ride_history_id', $rideHistory->ride_history_id)->first();

        return response()->json($updated_ride_history);
    }

    public function tripsMade(Request $request)
    {

        return Response::json(array(
            'trips_made' => RideHistory::where('service_id', request('service_id'))->get()->count()
        ));
    }

    public function pendingRide(Request $request)
    {
        if ($request->has('customer_id')) {

            $ride_history = RideHistory::where('customer_id', request('customer_id'))
                ->whereNull('ride_histories.end_time')
                ->where('ride_histories.ride_cancelled', 0)
                ->latest('created_at')
                ->get()
                ->first();

            if ($ride_history) {
                return Response::json(array(
                    'ride_history' => $ride_history,
                    'customer' => Customer::find(request('customer_id')),
                    'provider' => Provider::find(
                        Service::find($ride_history->service_id)->provider_id
                    )
                ));
            } else {
                return Response::json(array(
                    'no_pending_ride' => true
                ));
            }
        } else {
            $ride_history = DB::table('ride_histories')
                ->join('services', 'services.service_id', '=', 'ride_histories.service_id')
                ->join('providers', 'providers.provider_id', '=', 'services.provider_id')
                ->select('ride_histories.*')
                ->where('providers.provider_id', request('provider_id'))
                ->where('ride_histories.ride_cancelled', 0)
                ->whereNull('ride_histories.end_time')
                ->latest('created_at')
                ->get()
                ->first();

            if ($ride_history) {
                return Response::json(array(
                    'ride_history' => $ride_history,
                    'customer' => Customer::find(request('customer_id')),
                    'provider' => Provider::find(
                        Service::find($ride_history->service_id)->provider_id
                    )
                ));
            } else {
                return Response::json(array(
                    'no_pending_ride' => true
                ));
            }
        }
    }

    public function cancelLatestPendingRide(Request $request)
    {
        $rideHistory = RideHistory::where('customer_id', request('customer_id'))
            ->latest('created_at')
            ->get()
            ->first();

        if ($rideHistory) {
            if ($rideHistory->ride_cancelled == 0) {
                $customer = Customer::find($rideHistory->customer_id);

                $curl = curl_init();
                $FCM_API_KEY = env("FCM_API_KEY");
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{\"data\":{\"type\":\"ride_cancelled_by_customer\",\"title\":\"Ride Cancelled\",\"ride_history_id\":\"{$rideHistory->ride_history_id}\"},\"android\":{\"priority\":\"normal\"},\"registration_ids\":[\"{$customer->confirmation_token}\"]}",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Authorization: key={$FCM_API_KEY}"
                    ),
                ));

                $response = curl_exec($curl);

                Log::info('status_response', [
                    'notify_ride_cancelled_by_customer' => $response
                ]);

                curl_close($curl);

                $rideHistory->update(["ride_cancelled" => 1]);
            }
        }

        return Response::json(array(
            'ride_history' => $rideHistory
        ));
    }

    public function unfinishedRideCheck(Request $request)
    {
        $ride_history = RideHistory::where('customer_id', request("customer_id"))
            ->latest('created_at')
            ->first();

        if ($ride_history != null && $ride_history->ride_cancelled == 0 && $ride_history->end_time == null) {
            return Response::json(array(
                'ride_history' => $ride_history,
                'provider' => Provider::find(
                    Service::find($ride_history->service_id)->provider_id
                )
            ));
        }
        else {
            return Response::json(array(
                'ride_history' => null
                )
            );
        }
    }
}
