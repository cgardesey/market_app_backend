<?php

namespace App\Http\Controllers;

use App\InstructorCourse;
use App\InstructorCourseRating;
use App\ServiceRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ServiceRatingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service_rating_id = Str::uuid();
        ServiceRating::forceCreate(
            ['service_rating_id' => $service_rating_id] +
            $request->all());

        return  ServiceRating::where('service_rating_id', $service_rating_id)->first();
    }
}
