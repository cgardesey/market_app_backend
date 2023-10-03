<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Provider;
use App\RideHistory;
use App\RideStop;
use App\Service;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class RideStopController extends Controller
{
    public function scopedRideStops(Request $request)
    {
        $provider = Provider::find(
            Service::find(request('service_id'))->provider_id
        );

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
            'ride_history_id' => request('ride_history_id'),
        ]);

        $ride_history = RideHistory::find(request('ride_history_id'));
        $ride_history->update(
            [
                "end_time" => Carbon::now()
            ]
        );
        $ride_history = RideHistory::find(request('ride_history_id'));

        $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $ride_history->start_time);
        $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $ride_history->end_time);


        return Response::json(array(
            'destination_stop' => RideStop::where('tag', "destination")
                ->where('ride_history_id', request('ride_history_id'))
                ->first(),
            'stops' => RideStop::where('ride_history_id', request('ride_history_id'))
                ->get(),
            'time' => $end_time->diffInRealMinutes($start_time),
            'customer_confirmation_token' => $end_time->diffInRealMinutes($start_time),
        ));
    }
}
