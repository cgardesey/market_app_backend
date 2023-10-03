<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Customer;
use App\Provider;
use App\ServiceCategory;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    use UploadTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer_id = Str::uuid();
        Customer::forceCreate(
            ['customer_id' => $customer_id] +
            $request->all());

        $customer = Customer::where('customer_id', $customer_id)->first();

        $banners = Banner::All();
        $service_categories = ServiceCategory::where('description', "")->get();

        return Response::json(array(
            'customer' => $customer,
            'banners' => $banners,
            'service_categories' => $service_categories,
            'physical_address' => request('physical_address'),
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return $customer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        if ($request->has("confirmation_token")) {
            $customer->update(
                ['confirmation_token' => request("confirmation_token")]
            );
        }
        else {
            $attributes = [];
            if($request->hasFile("profile_image_file")) {
                // Define folder path
                $folder = '/uploads/customer-profile-images/';// Get image file
                $image = $request->file("profile_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $customer->customer_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $customer->customer_id);

                $attributes = $attributes + ['profile_image_url' => asset('storage/app') . "$filePath"];
            }

            $long = request('longitude');
            $lat = request('latitude');

            /*$curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://ghanapostgps.sperixlabs.org/get-address',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => "long={$long}&lat={$lat}",
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $decoded_response = json_decode($response);

            $street_address = "Unknown Location";
            $digital_address = "";
            if ($decoded_response->found) {
                $street_address = $decoded_response->data->Table[0]->Street;
                $digital_address = $decoded_response->data->Table[0]->GPSName;
            }*/

            $attributes = $attributes + [
                    'name' => request('name'),
                    'gender' => request('gender'),
                    'primary_contact' => request('primary_contact'),
                    'auxiliary_contact' => request('auxiliary_contact'),
                    'longitude' => $long,
                    'latitude' => $lat,
                    /*'digital_address' => $digital_address,
                    'street_address' => $street_address,*/
                ];

            $customer->update(
                $attributes
            );
        }

        $updated_customer = Customer::where('customer_id', $customer->customer_id)->first();

        return $updated_customer;
    }
}
