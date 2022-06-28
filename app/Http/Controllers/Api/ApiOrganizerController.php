<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ApiOrganizerController extends Controller
{
    public function getEventDetailById($id)
    {
        $event = DB::table('events')->where('id', $id);
        $is_exists = $event->exists();
        if ($is_exists) {
            $eventById = $event
                ->select("id", "name", "description", "content", "start_date", "end_date", "location", "photo", "link", "category_id", "popular_place_id")
                ->selectRaw("X(position) as lng,Y(position) as lat")
                ->find($id);
            return response()->json($eventById);
        } else {
            return response(['error' => true, 'message' => 'Not found'], 404);
        }
    }
}
