<?php

namespace App\Http\Controllers;

use App\Service;
use App\Enrolment;
use App\Institution;
use App\InstitutionFee;
use App\InstructorService;
use App\Payment;
use App\ServiceCategory;
use App\ServiceImage;
use App\SubscriptionChangeRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ServiceController extends Controller
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
                return Service::all();
            case 'instructor':
                return $user->info->services;
            case 'student':
//                return Service::setEagerLoads([])->get();
                $instructor_services = $user->info->instructorServices;
                $services = [];
                foreach ($instructor_services as $instructor_service) {
                    $services[] = $instructor_service->service;
                }
                return $services;
            default:
                'default';
                break;
        }
    }

    public function subServices(Request $request)
    {
        $ServiceCategory = ServiceCategory::where("title", request('service_category'))->first();
        if ($ServiceCategory) {
            $ServiceCategory->service_category_id;
            return DB::table('services')
                ->join('providers', 'services.provider_id', '=', 'providers.provider_id')
                ->join('service_images', 'services.service_id', '=', 'service_images.service_id')
                ->select('services.*', 'providers.longitude', 'providers.latitude', 'providers.provider_name', 'providers.title', 'providers.first_name', 'providers.last_name', 'providers.other_name', 'providers.verified', 'providers.availability', 'service_images.url')
                ->where('services.service_category', 'LIKE', '%' . request('service_category') . '%')
                ->where('service_images.featured_image', 1)
                ->get();
        }
        return [];
    }

    public function filteredServices(Request $request)
    {
        return DB::table('services')
            ->join('providers', 'services.provider_id', '=', 'providers.provider_id')
            ->join('service_images', 'services.service_id', '=', 'service_images.service_id')
            ->select('services.*', 'providers.longitude', 'providers.latitude', 'providers.provider_name', 'providers.title', 'providers.first_name', 'providers.last_name', 'providers.other_name', 'providers.verified', 'providers.availability', 'service_images.url')
            ->where('services.service_category', 'LIKE', '%' . request('search') . '%')
            ->where('service_images.featured_image', 1)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service_id = Str::uuid();
        Service::forceCreate(
            ['service_id' => $service_id] +
            $request->all());

        $service = service::where('service_id', $service_id)->first();

        return response()->json($service);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Service $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        return $service;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Service $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $service->update($request->all());

        $updated_service = Service::where('service_id', $service->service_id)->first();

        return response()->json($updated_service);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Service $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $status = $service->delete();
        return Response::json(array(
            'status' => $status
        ));
    }
}
