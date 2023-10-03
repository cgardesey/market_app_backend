<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Cart;
use App\Chat;
use App\Customer;
use App\Product;
use App\ProductCategory;
use App\Provider;
use App\ProviderCategory;
use App\RideHistory;
use App\RideStop;
use App\Service;
use App\ServiceCategory;
use App\User;
use App\Enrolment;
use App\Institution;
use App\InstitutionFee;
use App\InstructorUser;
use App\Payment;
use App\SubscriptionChangeRequest;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\RequestException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $role = $user->role;

        switch ($role) {
            case 'admin':
                return User::all();
            case 'instructor':
                return $user->info->users;
            case 'student':
//                return User::setEagerLoads([])->get();
                $instructor_users = $user->info->instructorUsers;
                $users = [];
                foreach ($instructor_users as $instructor_user) {
                    $users[] = $instructor_user->user;
                }
                return $users;
            default:
                'default';
                break;
        }
    }


    public function sendOtp()
    {
        $phone_number = request('phone_number');

        $user = User::where('phone_number', $phone_number)->first();

        $already_registered = false;
        if (!$user) {
            $already_registered = false;
            $userid = Str::uuid();
            $user = User::forceCreate([
                'user_id' => $userid,
                'phone_number' => $phone_number,
                'role' => 'provider',
                'api_token' => Str::uuid()
            ]);
        } else {
            $already_registered = true;
        }
        $hash = request('hash');
        $client = new Client();
        $otp = mt_rand(1000, 9999);
//        $content = "<#> Your OTP is: $otp $hash";
//        $content = urlencode($content);

        $user->update([
            'otp' => $otp
        ]);

        $this->sendSmsGuzzleRequest($phone_number, "Your pin is: {$otp}", new Client());

        return response()->json(array(
            'otp' => $otp,
            'user_id' => $user->user_id,
            'api_token' => $user->api_token,
            'already_registered' => $already_registered
        ));
    }

    public function getOtp(Request $request)
    {
        $user = User::where('phone_number', '=', request('phone_number'))->first();
        $otp = $user->otp;
        $customer = Customer::where('user_id', $user->user_id)->first();
        $providers = Provider::where('user_id', $user->user_id)
//            ->where('category', '!=', 'Rider')
            ->get();

        $riders = DB::table('services')
            ->join('providers', 'services.provider_id', '=', 'providers.provider_id')
            ->join('users', 'users.user_id', '=', 'providers.user_id')
            ->select('providers.*', 'services.service_category')
            ->where('users.user_id', $user->user_id)
            ->where('providers.category', 'Rider')
            ->get();
        return response()->json(array(
            'otp' => $otp,
            'user_id' => $user->user_id,
            'api_token' => $user->api_token,
            'user' => $user,
            'customer' => $customer,
            'providers' => $providers,
//            'riders' => $riders,
        ));
    }

    public function changeNumberSendOtp()
    {
        $old_phone_number = request('old_phone_number');
        $new_phone_number = request('new_phone_number');

        $user = User::where('phone_number', $old_phone_number)->first();

        if (!$user) {
            return response()->json(array(
                'user_not_found' => true,
            ));
        }
        $hash = request('hash');
        $client = new Client();
        $otp = mt_rand(1000, 9999);
//        $content = "<#> Your OTP is: $otp $hash";
//        $content = urlencode($content);

        $user->update([
            'otp' => $otp
        ]);

        $this->sendSmsGuzzleRequest($new_phone_number, "Your pin is: {$otp}", new Client());

        return response()->json(array(
            'otp' => $otp
        ));
    }

    public function changeNumberGetOtp(Request $request)
    {
        $user = User::where('phone_number', '=', request('old_phone_number'))->first();

        $verified = $user->otp == request('entered_otp');

        if ($verified) {
            $user->update([
                'phone_number' => request('new_phone_number')
            ]);
        }

        return response()->json(array(
            'otp' => $user->otp,
            'verified' => $verified,
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userid = Str::uuid();
        User::forceCreate(
            ['user_id' => $userid] +
            $request->all());

        $user = user::where('user_id', $userid)->first();

        return response()->json($user);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        $updated_user = User::where('user_id', $user->user_id)->first();

        return response()->json($updated_user);
    }


    public function fetchCustomerHomeData(Request $request)
    {
        $banners = Banner::All();
        $service_categories = ServiceCategory::where('description', "")->get();
        $product_categories = ProductCategory::where('description', "")->get();

        $scoped_carts = DB::table('carts')
            ->leftJoin('payments', 'carts.cart_id', '=', 'payments.cart_id')
            ->join('providers', 'carts.provider_id', '=', 'providers.provider_id')
            ->join('customers', 'customers.customer_id', '=', 'carts.customer_id')
            ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
            ->select('carts.*', 'payments.status AS status', 'providers.longitude AS provider_longitude', 'providers.latitude AS provider_latitude', 'providers.provider_name', 'providers.title AS provider_title', 'providers.first_name AS provider_first_name', 'providers.last_name AS provider_last_name', 'providers.other_name AS provider_other_name', 'providers.verified', 'providers.availability AS provider_availability', 'providers.profile_image_url AS provider_image_url', 'customers.longitude AS customer_longitude', 'customers.latitude AS customer_latitude', 'customers.name AS customer_name', 'customers.profile_image_url AS customer_image_url', DB::raw("count(cart_products.id) as item_count"))
            ->groupBy('carts.id')
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.cart_id)) OR payments.id IS NULL) AND carts.customer_id = ?', [request('customer_id')])
            ->get();

        $sub = Chat::where('customer_id', request('customer_id'));
        $chats = DB::table('chats')
            ->join('customers', 'customers.customer_id', '=', 'chats.customer_id')
            ->join('providers', 'providers.provider_id', '=', 'chats.provider_id')
            ->select('chats.*', 'customers.name as customer_name', 'customers.profile_image_url as customer_profile_image_url', 'providers.provider_name', 'providers.title AS provider_title', 'providers.first_name AS provider_first_name', 'providers.last_name AS provider_last_name', 'providers.other_name AS provider_other_name', 'providers.profile_image_url AS provider_image_url')
            ->whereRaw('chats.id IN (SELECT MAX(chats.id) FROM chats GROUP BY providers.provider_id) AND customers.customer_id = ?', [request('customer_id')])
            ->get();

        $ride_history = RideHistory::where('customer_id', request("customer_id"))
            ->latest('created_at')
            ->first();

        if ($ride_history != null && $ride_history->ride_cancelled == 0 && $ride_history->end_time == null) {
            return Response::json(array(
                'banners' => $banners,
                'service_categories' => $service_categories,
                'product_categories' => $product_categories,
                'scoped_carts' => $scoped_carts,
                'chats' => $chats,

                'ride_history' => $ride_history,
                'provider' => Provider::find(
                    Service::find($ride_history->service_id)->provider_id
                )
            ));
        }

        return Response::json(array(
            'banners' => $banners,
            'service_categories' => $service_categories,
            'product_categories' => $product_categories,
            'scoped_carts' => $scoped_carts,
            'chats' => $chats,
        ));
    }

    public function fetchProviderHomeData(Request $request)
    {
        $banners = Banner::All();
        $service_categories = ServiceCategory::where('description', "main")->get();
        $products = Product::where('provider_id', request("provider_id"))->get();
        $services = Service::where('provider_id', request("provider_id"))->get();

        $product_images = DB::table('products')
            ->join('providers', 'products.provider_id', '=', 'providers.provider_id')
            ->join('product_images', 'products.product_id', '=', 'product_images.product_id')
            ->select('product_images.*')
            ->where('providers.provider_id', request('provider_id'))
            ->get();

        $service_images = DB::table('services')
            ->join('providers', 'services.provider_id', '=', 'providers.provider_id')
            ->join('service_images', 'services.service_id', '=', 'service_images.service_id')
            ->select('service_images.*')
            ->where('providers.provider_id', request('provider_id'))
            ->get();

        $scoped_carts = DB::table('carts')
            ->leftJoin('payments', 'carts.cart_id', '=', 'payments.cart_id')
            ->join('providers', 'carts.provider_id', '=', 'providers.provider_id')
            ->join('customers', 'customers.customer_id', '=', 'carts.customer_id')
            ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
            ->select('carts.*', 'payments.status AS status', 'providers.longitude AS provider_longitude', 'providers.latitude AS provider_latitude', 'providers.provider_name', 'providers.title AS provider_title', 'providers.first_name AS provider_first_name', 'providers.last_name AS provider_last_name', 'providers.other_name AS provider_other_name', 'providers.verified', 'providers.availability AS provider_availability', 'providers.profile_image_url AS provider_image_url', 'customers.longitude AS customer_longitude', 'customers.latitude AS customer_latitude', 'customers.name AS customer_name', 'customers.profile_image_url AS customer_image_url', DB::raw("count(cart_products.id) as item_count"))
            ->groupBy('carts.id')
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.cart_id)) OR payments.id IS NULL) AND carts.provider_id = ?', [request('provider_id')])
            ->get();

        $chats = DB::table('chats')
            ->join('customers', 'customers.customer_id', '=', 'chats.customer_id')
            ->join('providers', 'providers.provider_id', '=', 'chats.provider_id')
            ->select('chats.*', 'customers.name as customer_name', 'customers.profile_image_url as customer_profile_image_url', 'providers.provider_name', 'providers.title AS provider_title', 'providers.first_name AS provider_first_name', 'providers.last_name AS provider_last_name', 'providers.other_name AS provider_other_name', 'providers.profile_image_url AS provider_image_url')
            ->whereRaw('chats.id IN (SELECT MAX(chats.id) FROM chats GROUP BY customers.customer_id) AND providers.provider_id = ?', [request('provider_id')])
            ->get();

        $ride_history = RideHistory::where('customer_id', request("customer_id"))
            ->latest('created_at')
            ->first();

        $ride_history = DB::table('ride_histories')
            ->join('services', 'services.service_id', '=', 'ride_histories.service_id')
            ->join('providers', 'providers.provider_id', '=', 'services.provider_id')
            ->select('ride_histories.*')
            ->where('providers.provider_id', request('provider_id'))
            ->latest('ride_histories.created_at')
            ->get()
            ->first();

        if ($ride_history != null && $ride_history->ride_cancelled == 0 && $ride_history->end_time == null) {
            return Response::json(array(
                'banners' => $banners,
                'service_categories' => $service_categories,
                'products' => $products,
                'services' => $services,
                'product_images' => $product_images,
                'service_images' => $service_images,
                'scoped_carts' => $scoped_carts,
                'chats' => $chats,

                'ride_history' => $ride_history,
                'customer' => Customer::find($ride_history->customer_id)
            ));
        }

        return Response::json(array(
            'banners' => $banners,
            'service_categories' => $service_categories,
            'products' => $products,
            'services' => $services,
            'product_images' => $product_images,
            'service_images' => $service_images,
            'scoped_carts' => $scoped_carts,
            'chats' => $chats,
        ));
    }

    public function chatData(Request $request)
    {
        $cart = DB::table('carts')
            ->leftJoin('payments', 'carts.cart_id', '=', 'payments.cart_id')
            ->join('providers', 'carts.provider_id', '=', 'providers.provider_id')
            ->join('customers', 'customers.customer_id', '=', 'carts.customer_id')
            ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
            ->select('carts.*', 'payments.status AS status', 'providers.longitude AS provider_longitude', 'providers.latitude AS provider_latitude', 'providers.provider_name', 'providers.title AS provider_title', 'providers.first_name AS provider_first_name', 'providers.last_name AS provider_last_name', 'providers.other_name AS provider_other_name', 'providers.verified', 'providers.availability AS provider_availability', 'providers.profile_image_url AS provider_image_url', 'customers.longitude AS customer_longitude', 'customers.latitude AS customer_latitude', 'customers.name AS customer_name', 'customers.profile_image_url AS customer_image_url', DB::raw("count(cart_products.id) as item_count"))
            ->groupBy('carts.id')
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.cart_id)) OR payments.id IS NULL) AND carts.customer_id = ? AND carts.provider_id = ?', [request('customer_id'), request('provider_id')])
            ->get()
            ->first();


        return Response::json(array(
            'cart' => $cart,
            'chats' => Chat::where('customer_id', request('customer_id'))
                ->where('provider_id', request('provider_id'))
                ->where('id', '>', request('id'))
                ->get()
        ));
    }

    public function providerOrderId(Request $request)
    {
        $chat = Chat::where('provider_id', request("provider_id"))
            ->where('customer_id', request("customer_id"))
            ->latest('created_at')
            ->first();

        if ($request->has('order_id')) {
            $order_id = "Order id: " . request('order_id');
            if ($chat == null || $chat->text != $order_id) {
                Chat::forceCreate([
                    'chat_id' => request('chat_id'),
                    'tag' => "order_id",
                    'customer_id' => request('customer_id'),
                    'provider_id' => request('provider_id'),
                    'text' => $order_id,
                    'sent_by_customer' => 1,
                ]);
            }
        }

        return Provider::find(request('provider_id'));
    }

    public function groupCall(Request $request)
    {
        // Call customer and Provider into conference call
        $sanitized_customer_phone_number = '';
        $sanitized_Provider_phone_number = '';
        if ($request->has('customer_id')) {
            $sanitized_Provider_phone_number = substr(request("phone_number"), 1);
            $sanitized_customer_phone_number = substr(Customer::find(request("customer_id"))->primary_contact, 1);
        } else {
            $sanitized_customer_phone_number = substr(request("phone_number"), 1);
            $sanitized_Provider_phone_number = substr(Provider::find(request("provider_id"))->primary_contact, 1);
        }

        $client = new Client();
        $data = [
            'type' => 'bulk',
            'room_number' => '20000000',
            'session_file_name' => 'test_call',
            'participant' => "{$sanitized_customer_phone_number},{$sanitized_Provider_phone_number}",
        ];

        try {
            $response = $client->request('POST', env("CALL_API_URL"), [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
        } catch (RequestException $e) {
            // Handle exceptions here
        }

        return Response::json(array(
            'success' => true,
            'curl_response' => $response,
        ));
    }

    public function userPhoneNumber(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();

        return Response::json(array(
            'phone_number' => $user->phone_number,
        ));
    }

    public function pickupAddressAndNearbyCars(Request $request)
    {
        $long = request("long");
        $lat = request("lat");
        $service_category = request("service_category");

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
            CURLOPT_POSTFIELDS => "long={$long}&lat={$lat}",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $decoded_response = json_decode($response);

        if ($decoded_response->found) {
            $street_address = $decoded_response->data->Table[0]->Street;
        } else {
            $street_address = "Unknown Location";
        }

        $nearby_locations = DB::select('SELECT latitude, longitude FROM (SELECT *, (((ACOS(SIN(( ? * PI() / 180))*SIN(( latitude * PI() / 180)) + COS(( ? * PI() /180 ))*COS(( longitude * PI() / 180)) * COS((( ? - longitude) * PI()/180)))) * 180/PI()) * 60 * 1.1515 * 1.609344)AS distance FROM providers) providers INNER JOIN services ON services.provider_id=providers.provider_id WHERE distance <= 25000 AND services.service_category = ? AND providers.availability = ? LIMIT 10', [$long, $long, $lat, $service_category, 'Available']);

        return Response::json(array(
            'street_address' => $street_address,
            'nearby_locations' => $nearby_locations,
        ));
    }

    public function nearestRider(Request $request)
    {
        $pickup_long = request("pickup_long");
        $pickup_lat = request("pickup_lat");
        $radius = request("radius");

        $service_category = request("service_category");

        $riders = DB::select('SELECT * FROM (SELECT *, ( (( ACOS(SIN(( ? * PI() / 180))*SIN(( latitude * PI() / 180)) + COS(( ? * PI() /180 ))*COS(( longitude * PI() / 180)) * COS((( ? - longitude) * PI()/180)))) * 180/PI()) * 60 * 1.1515 * 1.609344)AS distance FROM providers) providers INNER JOIN services ON services.provider_id=providers.provider_id WHERE distance <= ?  AND services.service_category = ?  AND providers.availability = ? ORDER  BY distance LIMIT 1', [$pickup_long, $pickup_long, $pickup_lat, $radius, $service_category, 'Available']);

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
            CURLOPT_POSTFIELDS => "long={$pickup_long}&lat={$pickup_lat}",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $decoded_response = json_decode($response);

        if ($decoded_response->found) {
            $pickup_address = $decoded_response->data->Table[0]->Street;
        } else {
            $pickup_address = "Unknown Location";
        }


        $destination_long = request("destination_long");
        $destination_lat = request("destination_lat");

        $curl2 = curl_init();
        curl_setopt_array($curl2, array(
            CURLOPT_URL => 'https://ghanapostgps.sperixlabs.org/get-address',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "long={$destination_long}&lat={$destination_lat}",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response2 = curl_exec($curl2);

        curl_close($curl2);

        $decoded_response2 = json_decode($response2);

        if ($decoded_response2->found) {
            $destination_address = $decoded_response2->data->Table[0]->Street;
        } else {
            $destination_address = "Unknown Location";
        }


        if (sizeof($riders) == 0) {
            return Response::json(array(
                'no_riders_available' => true
            ));
        }
        $service_id = Service::where('provider_id', $riders[0]->provider_id)
            ->where('service_category', request("service_category"))
            ->first()
            ->service_id;

        $ride_history_id = Str::uuid();
        $ride_history = RideHistory::forceCreate([
            'ride_history_id' => $ride_history_id,
            'pickup_latitude' => $pickup_lat,
            'pickup_longitude' => $pickup_long,
            'destination_latitude' => $pickup_lat,
            'destination_longitude' => $pickup_long,
            'pickup_address' => $pickup_address,
            'destination_address' => $destination_address,
            'service_id' => $service_id,
            'customer_id' => request('customer_id')
        ]);

        $customer = Customer::find(request('customer_id'));

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
            CURLOPT_POSTFIELDS => "{\"data\":{\"type\":\"ride_request\",\"title\":\"Ride Request Received\",\"ride_history_id\":\"{$ride_history->ride_history_id}\",\"distance\":\"{$riders[0]->distance}\",\"pickup_lat\":\"{$pickup_lat}\",\"pickup_long\":\"{$pickup_long}\",\"destination_lat\":\"{$destination_lat}\",\"destination_long\":\"{$destination_long}\",\"customer_name\":\"{$customer->name}\",\"customer_profile_image_url\":\"{$customer->profile_image_url}\",\"pickup_address\":\"{$pickup_address}\",\"destination_address\":\"{$destination_address}\",\"service_id\":\"{$service_id}\",\"customer_primary_contact\":\"{$customer->primary_contact}\",\"customer_confirmation_token\":\"{$customer->confirmation_token}\",\"customer_id\":\"{$customer->customer_id}\",\"provider_id\":\"{$riders[0]->provider_id}\"},\"android\":{\"priority\":\"normal\"},\"registration_ids\":[\"{$riders[0]->confirmation_token}\"]}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: key={$FCM_API_KEY}"
            ),
        ));

        $response = curl_exec($curl);

        Log::info('status_response', [
            'notify_driver_response' => $response
        ]);

        curl_close($curl);

        return Response::json(array(
            'riders' => $riders,
            'street_address' => $pickup_address,
            'service_id' => $service_id,
            'ride_history' => $ride_history,
            'response' => $response,
        ));
    }

    public function updateConfirmationToken(Request $request, User $user)
    {
        $user->update($request->all());
    }

    /**
     * @param string $phone_number
     * @param string $sms_message
     * @param Client $client
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSmsGuzzleRequest(string $phone_number, string $sms_message, Client $client)
    {
        $token = env("SMS_API_TOKEN");

        $response = $client->post(env("SMS_API_URL"), [
            'headers' => [
                'Authorization' => 'Basic ' . $token,
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'msisdn' => $phone_number,
                'message' => $sms_message,
                'senderId' => 'Ekumfi',
            ],
            'verify' => false,
        ]);
        /*Log::info('$response', [
            $response->getStatusCode()
        ]);*/
        return $response->getBody()->getContents();
    }
}
