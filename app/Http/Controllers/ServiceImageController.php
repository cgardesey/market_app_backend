<?php

namespace App\Http\Controllers;

use App\Image;
use App\Enrolment;
use App\Provider;
use App\ServiceImage;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ServiceImageController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function scopedServiceImages(Request $request)
    {
        return ServiceImage::where('service_id', request('service_id'))->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service_image_id = Str::uuid();
        $attributes = [];
        if($request->hasFile("image_file")) {
            // Define folder path
            $folder = '/uploads/service-images/';// Get image file
            $image = $request->file("image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $service_image_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $service_image_id);

            $attributes = $attributes + ['url' => asset('storage/app') . "$filePath"];
        }
        $attributes = $attributes + [
                'service_image_id' => $service_image_id,
                'service_id' => request('service_id'),
                'featured_image' => request('featured_image'),
            ];

        ServiceImage::forceCreate(
            $attributes
        );

        $service_image = ServiceImage::where('service_image_id', $service_image_id)->first();

        return $service_image;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceImage  $serviceimage
     * @return \Illuminate\Http\Response
     */
    public function show($service_image_id)
    {
        $serviceimage = ServiceImage::where('service_image_id', $service_image_id)->first();

        return response()->json($serviceimage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceImage  $serviceimage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceImage $service_image)
    {
        DB::table('service_images')
            ->where('service_id', $service_image->service_id)
            ->update(['featured_image' => 0]);

        DB::table('service_images')
            ->where('service_image_id', $service_image->service_image_id)
            ->update(['featured_image' => 1]);

        return ServiceImage::where('service_id', $service_image->service_id)->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceImage  $serviceimage
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceImage $service_image)
    {
        $status = $service_image->delete();
        return Response::json(array(
            'status' => $status
        ));
    }
}
