<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\PlaceBoundary;
use Illuminate\Support\Facades\DB;

class ApiAdminController extends Controller
{
    public function placeBoundaries()
    {
        // get verified or done events
        $verified_events = DB::table('submitted_events')
            ->selectRaw("events.id as id, events.category_id, events.position")
            ->join('events', 'events.id', '=', 'submitted_events.event_id')
            ->where('submitted_events.status', 'verified')
            ->orWhere('submitted_events.status', 'done');


        // get all categories
        $categories = DB::table('categories')->select("id", "name")->get();
        // parse loaded categories to raw query 
        // input : [{id:1,name:"Pendidikan"}, {id:2,name:"Kebudayaan"}]
        // expected output : ", IF(verified_events.category_id = 1, COUNT(verified_events.id), 0) AS pendidikan", and so on . . . 
        $categories_to_raw_query = implode(collect($categories)->map(function ($category) {
            return ", IF(verified_events.category_id = $category->id, COUNT(verified_events.id), 0) AS " . strtolower($category->name);
        })->toArray());


        // load place boundary join with event table based on same location / region
        $boundaries = DB::table('place_boundaries')
            ->joinSub($verified_events, 'verified_events', function ($join) {
                $join->where(DB::raw('ST_WITHIN(verified_events.position, place_boundaries.polygon_area)'), true);
            })
            ->groupBy('place_boundaries.id')
            ->selectRaw(
                "place_boundaries.id
                , place_boundaries.name
                , COUNT(verified_events.id) AS total
                , ST_ASGEOJSON(place_boundaries.polygon_area) AS geom
                $categories_to_raw_query"
            )
            ->get();

        // return as full geojson
        return response()->json([
            "type" => "FeatureCollection",
            "features" => collect($boundaries)->map(function ($place) {
                return [
                    'type' => 'Feature',
                    'properties' => [
                        'id' => $place->id,
                        'region' => $place->name ?? '-',
                        'total' => $place->total ?? 0,

                        // this is a constanta if new category added then add to this line too
                        'pendidikan' => $place->pendidikan ?? 0,
                        'kebudayaan' => $place->kebudayaan ?? 0,
                    ],
                    'geometry' => json_decode($place->geom),
                ];
            })
        ]);
    }
}
