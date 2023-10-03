<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Instructor;
use App\Student;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;


class ChatController extends Controller
{
    use UploadTrait;

    public function scopedChats(Request $request)
    {
        $chats = Chat::where('customer_id', request('customer_id'))
            ->where('provider_id', request('provider_id'))
            ->where('id', '>', request('id'))
            ->get();

        return Response::json(array(
            'chats' => $chats
        ));
    }

    public function scopedLatestChats(Request $request): \Illuminate\Support\Collection
    {
        if ($request->has('customer_id')) {
            return DB::table('chats')
                ->join('customers', 'customers.customer_id', '=', 'chats.customer_id')
                ->join('providers', 'providers.provider_id', '=', 'chats.provider_id')
                ->select('chats.*', 'customers.name as customer_name', 'customers.profile_image_url as customer_profile_image_url', 'providers.provider_name', 'providers.title AS provider_title', 'providers.first_name AS provider_first_name', 'providers.last_name AS provider_last_name', 'providers.other_name AS provider_other_name', 'providers.profile_image_url AS provider_image_url')
                ->whereRaw('chats.id IN (SELECT MAX(chats.id) FROM chats GROUP BY providers.provider_id) AND customers.customer_id = ?', [request('customer_id')])
                ->get();
        }
        return DB::table('chats')
            ->join('customers', 'customers.customer_id', '=', 'chats.customer_id')
            ->join('providers', 'providers.provider_id', '=', 'chats.provider_id')
            ->select('chats.*', 'customers.name as customer_name', 'customers.profile_image_url as customer_profile_image_url', 'providers.provider_name', 'providers.title AS provider_title', 'providers.first_name AS provider_first_name', 'providers.last_name AS provider_last_name', 'providers.other_name AS provider_other_name', 'providers.profile_image_url AS provider_image_url')
            ->whereRaw('chats.id IN (SELECT MAX(chats.id) FROM chats GROUP BY providers.provider_id) AND customers.customer_id = ?', [request('provider_id')])
            ->get();
    }

}
