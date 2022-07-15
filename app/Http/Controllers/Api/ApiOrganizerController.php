<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

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



    public function validateEvent(Request $request)
    {
        $errors = [];

        // validate body request from frontend 
        // from front end sent body request as form
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:30',
            'description' => 'required|max:225',
            'category_id' => 'required|exists:App\Models\Category,id',
            'photo' => 'required|mimes:jpg,png|max:1024',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);


        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 422);
        }

        // max one week
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $moreThanOneWeek = $start_date->diffInDays($end_date) > 6;
        if ($moreThanOneWeek) {
            array_push($errors, ['start_date' => 'event can`t more than one week']);
            return response()->json($errors, 422);
        }


        // event must inside jogja 
        $not_in_popular_place = $request->popular_place_id == null ? true : false;
        if ($not_in_popular_place) {
            $lat = $request->lat;
            $lng = $request->lng;
            $event_as_point_text = "POINT($lng $lat)";
            // 
            $place_boundaries =
                collect([
                    "type" => "FeatureCollection",
                    "features" =>
                    collect(
                        DB::table('place_boundaries')
                            ->selectRaw('name, ST_ASGEOJSON(polygon_area) AS polygon')
                            ->get()
                    )->map(function ($place) {
                        return [
                            'type' => 'Feature',
                            'properties' => ['region' => $place->name],
                            'geometry' => json_decode($place->polygon),
                        ];
                    })
                ])->toJson();

            $native_query = "SELECT (
                ST_WITHIN(
                        GeomFromText('$event_as_point_text'),
                        ST_GeomFromText(ST_AsText(ST_GeomFromGeoJSON('$place_boundaries')))
                    )
                ) AS is_inside_jogja";
            $is_inside_jogja = DB::select($native_query)[0]->is_inside_jogja == 1 ? true : false;

            if ($is_inside_jogja == false) {
                array_push($errors, ['location' => 'Location must be in Yogyakarta province']);
                return response()->json($errors, 422);
            }
        }

        // check wheter others event already exists or not
        $is_event_already_exists = DB::table('events')
            ->whereRaw("(start_date between '$request->start_date' and '$request->end_date' OR end_date between '$request->start_date' and '$request->end_date' OR ('$request->start_date' between start_date and end_date))")
            ->whereRaw("ST_DISTANCE_SPHERE(position, ST_GEOMFROMTEXT('POINT($request->lng $request->lat)', 4326)) <= 0.5")
            ->whereRaw("(select status from submitted_events se where se.event_id = events.id order by id desc limit 1) = 'verified'")
            ->whereNull('deleted_at')
            ->exists();

        if ($is_event_already_exists) {
            array_push($errors, ['event' => 'another event already exists with the same location and time']);
            return response()->json($errors, 422);
        }

        if (count($errors) == 0) {
            return response()->json(['data' => true, 'status' => 'success', 'messages' => 'all data valid'], 200);
        }
    }


    public function submissionHistory($id)
    {
        $subs = DB::table('submitted_events')->where('event_id', $id);
        $is_exists = $subs->exists();
        if ($is_exists) {
            $subsByEventId = $subs
                ->select('id', 'status', 'reason')
                ->selectRaw("DATE_FORMAT(created_at, '%I:%i, %M %b %Y') as created_at")
                ->orderByDesc('created_at')
                ->get();
            return response()->json($subsByEventId);
        } else {
            return response(['error' => true, 'message' => 'Not found'], 404);
        }
    }


    public function uploadContentImage(Request $request)
    {
        $fileName = time() . '_' . $request->file('content-img')->getClientOriginalName();
        $filePath = $request->file('content-img')->storeAs('content-images', $fileName, 'public');
        return response()->json([
            'data' => [
                'url' => '/storage/' . $filePath
            ]
        ]);
    }
}
