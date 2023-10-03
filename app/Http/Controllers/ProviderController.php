<?php

namespace App\Http\Controllers;

use App\Provider;
use App\Enrolment;
use App\Institution;
use App\InstitutionFee;
use App\InstructorProvider;
use App\Payment;
use App\ProviderCategory;
use App\Service;
use App\SubscriptionChangeRequest;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProviderController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $user->update(['otp' => null]);

        if ($user->info == null) {
            Provider::forceCreate([
                'provider_id' => $user->userid
            ]);
        }
        return $user->info->load('user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $provider_id = Str::uuid();
        $attributes = [];
        if($request->hasFile("profile_image_file")) {
            // Define folder path
            $folder = '/uploads/provider-profile-images/';// Get image file
            $image = $request->file("profile_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $provider_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $provider_id);

            $attributes = $attributes + ['profile_image_url' => asset('storage/app') . "$filePath"];
        }

        if($request->hasFile("identification_image_file")) {
            // Define folder path
            $folder = '/uploads/provider-identification-images/';// Get image file
            $image = $request->file("identification_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $provider_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $provider_id);

            $attributes = $attributes + ['identification_image_url' => asset('storage/app') . "$filePath"];
        }

        if($request->hasFile("association_identification_image_file")) {
            // Define folder path
            $folder = '/uploads/provider-association-identification-images/';// Get image file
            $image = $request->file("association_identification_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $provider_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $provider_id);

            $attributes = $attributes + ['association_identification_image_url' => asset('storage/app') . "$filePath"];
        }

        if($request->hasFile("license_image_file")) {
            // Define folder path
            $folder = '/uploads/provider-licence-images/';// Get image file
            $image = $request->file("license_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $provider_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $provider_id);

            $attributes = $attributes + ['drivers_licence_image_url' => asset('storage/app') . "$filePath"];
        }
        if($request->hasFile("reverse_license_image_file")) {
            // Define folder path
            $folder = '/uploads/provider-reverse-licence-images/';// Get image file
            $image = $request->file("reverse_license_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $provider_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $provider_id);

            $attributes = $attributes + ['drivers_licence_reverse_image_url' => asset('storage/app') . "$filePath"];
        }
        if($request->hasFile("road_worthy_sticker_image_file")) {
            // Define folder path
            $folder = '/uploads/provider-road-worthy-sticker-images/';// Get image file
            $image = $request->file("road_worthy_sticker_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $provider_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $provider_id);

            $attributes = $attributes + ['road_worthy_sticker_image_url' => asset('storage/app') . "$filePath"];
        }
        if($request->hasFile("insurance_sticker_image_file")) {
            // Define folder path
            $folder = '/uploads/provider-insurance-sticker-sticker-images/';// Get image file
            $image = $request->file("insurance_sticker_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $provider_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $provider_id);

            $attributes = $attributes + ['insurance_sticker_image_url' => asset('storage/app') . "$filePath"];
        }

        $vehicle_type = request('vehicle_type');


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

        $decoded_response = json_decode($response);*/

        $street_address = "Unknown Location";
        $digital_address = "";
        /*if ($decoded_response->found) {
            $street_address = $decoded_response->data->Table[0]->Street;
            $digital_address = $decoded_response->data->Table[0]->GPSName;
        }*/

        $attributes = $attributes + [
                'provider_id' => $provider_id,
                'title' => request('title'),
                'first_name' => request('first_name'),
                'last_name' => request('last_name'),
                'other_name' => request('other_name'),
                'gender' => request('gender'),

                'provider_name' => request('provider_name'),

                'primary_contact' => request('primary_contact'),
                'auxiliary_contact' => request('auxiliary_contact'),
                'dob' => request('dob'),
                'longitude' => $long,
                'latitude' => $lat,
                'digital_address' => $digital_address,
                'street_address' => $street_address,
                'marital_status' => request('marital_status'),

                'years_of_operation' => request('years_of_operation'),

                'identification_type' => request('identification_type'),
                'identification_number' => request('identification_number'),

                'tin_number' => request('tin_number'),
                'date_registered' => request('date_registered'),

                'association_identification_number' => request('association_identification_number'),

                'vehicle_type' => $vehicle_type,
                'vehicle_registration_number' => request('vehicle_registration_number'),
                'drivers_licence_image_url' => request('drivers_licence_image_url'),
                'drivers_licence_reverse_image_url' => request('drivers_licence_reverse_image_url'),
                'road_worthy_sticker_image_url' => request('road_worthy_sticker_image_url'),
                'insurance_sticker_image_url' => request('insurance_sticker_image_url'),

                'category' => request('category'),
                'user_id' => request('user_id')
            ];

        Provider::forceCreate(
            $attributes
        );

        if ($vehicle_type != null) {
            Service::forceCreate(
                [
                    'service_id' => Str::uuid(),
                    'service_category' =>  "SuperRide >> {$vehicle_type}",
                    'provider_id' => $provider_id,
                ]
            );
        }

        $provider = provider::where('provider_id', $provider_id)->first();

        return response()->json($provider);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Provider $provider
     * @return \Illuminate\Http\Response
     */
    public function show(Provider $provider)
    {
        return $provider;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Provider $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provider $provider)
    {

        if ($request->has("availability")) {
            $provider->update(
                ['availability' => request("availability")]
            );
        }
        elseif ($request->has("confirmation_token")) {
            $provider->update(
                ['confirmation_token' => request("confirmation_token")]
            );
        }
        else {
            $attributes = [];
            if ($request->hasFile("profile_image_file")) {
                // Define folder path
                $folder = '/uploads/provider-profile-images/';// Get image file
                $image = $request->file("profile_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $provider->provider_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $provider->provider_id);

                $attributes = $attributes + ['profile_image_url' => asset('storage/app') . "$filePath"];
            }
            if ($request->hasFile("identification_image_file")) {
                // Define folder path
                $folder = '/uploads/provider-identification-images/';// Get image file
                $image = $request->file("identification_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $provider->provider_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $provider->provider_id);

                $attributes = $attributes + ['identification_image_url' => asset('storage/app') . "$filePath"];
            }
            if ($request->hasFile("association_identification_image_file")) {
                // Define folder path
                $folder = '/uploads/provider-association-identification-images/';// Get image file
                $image = $request->file("association_identification_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $provider->provider_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $provider->provider_id);

                $attributes = $attributes + ['association_identification_image_url' => asset('storage/app') . "$filePath"];
            }
            if($request->hasFile("license_image_file")) {
                // Define folder path
                $folder = '/uploads/provider-licence-images/';// Get image file
                $image = $request->file("license_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $provider->provider_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $provider->provider_id);

                $attributes = $attributes + ['drivers_licence_image_url' => asset('storage/app') . "$filePath"];
            }
            if($request->hasFile("reverse_license_image_file")) {
                // Define folder path
                $folder = '/uploads/provider-reverse-licence-images/';// Get image file
                $image = $request->file("reverse_license_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $provider->provider_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $provider->provider_id);

                $attributes = $attributes + ['drivers_licence_reverse_image_url' => asset('storage/app') . "$filePath"];
            }
            if($request->hasFile("road_worthy_sticker_image_file")) {
                // Define folder path
                $folder = '/uploads/provider-road-worthy-sticker-images/';// Get image file
                $image = $request->file("road_worthy_sticker_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $provider->provider_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $provider->provider_id);

                $attributes = $attributes + ['road_worthy_sticker_image_url' => asset('storage/app') . "$filePath"];
            }
            if($request->hasFile("insurance_sticker_image_file")) {
                // Define folder path
                $folder = '/uploads/provider-insurance-sticker-sticker-images/';// Get image file
                $image = $request->file("insurance_sticker_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $provider->provider_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $provider->provider_id);

                $attributes = $attributes + ['insurance_sticker_image_url' => asset('storage/app') . "$filePath"];
            }

            $attributes = $attributes + [
                    'title' => request('title'),
                    'first_name' => request('first_name'),
                    'last_name' => request('last_name'),
                    'other_name' => request('other_name'),
                    'gender' => request('gender'),

                    'provider_name' => request('provider_name'),

                    'primary_contact' => request('primary_contact'),
                    'auxiliary_contact' => request('auxiliary_contact'),
                    'dob' => request('dob'),
                    'longitude' => request('longitude'),
                    'latitude' => request('latitude'),
                    'digital_address' => request('digital_address'),
                    'street_address' => request('street_address'),
                    'marital_status' => request('marital_status'),

                    'years_of_operation' => request('years_of_operation'),

                    'identification_type' => request('identification_type'),
                    'identification_number' => request('identification_number'),

                    'tin_number' => request('tin_number'),
                    'date_registered' => request('date_registered'),

                    'association_identification_number' => request('association_identification_number'),

                    'vehicle_type' => request('vehicle_type'),
                    'vehicle_registration_number' => request('vehicle_registration_number'),
                    'drivers_licence_image_url' => request('drivers_licence_image_url'),
                    'drivers_licence_reverse_image_url' => request('drivers_licence_reverse_image_url'),
                    'road_worthy_sticker_image_url' => request('road_worthy_sticker_image_url'),
                    'insurance_sticker_image_url' => request('insurance_sticker_image_url'),

                    'category' => request('category'),
                    'user_id' => request('user_id')
                ];
            $context = request()->all();//        Log::info('request', $context);
            $provider->update(
                $attributes
            );
        }

        $updated_provider = Provider::where('provider_id', $provider->provider_id)->first();

        return $updated_provider;
    }
}
