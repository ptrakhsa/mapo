<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\PopularPlaces;
use App\Http\Resources\Place as PlaceResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Event, App\Models\SubmittedEvent;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Support\Facades\Validator;

class ApiEventController extends Controller
{
    public function findEventDetail($id)
    {
        $detail = DB::table('events')
            ->join('categories', 'events.category_id', '=', 'categories.id')
            ->join('organizers', 'events.organizer_id', '=', 'organizers.id')
            ->select("events.id", "events.name", "events.description", "events.content", "events.location", "events.photo", "events.link")
            ->addSelect('categories.name AS category_name', 'organizers.name AS organizer_name')
            ->selectRaw(
                "X(position) as lng
                 ,Y(position) as lat
                 ,DATE_FORMAT(events.start_date, '%I:%i %p, %e %M') as start_date
                 ,DATE_FORMAT(events.end_date, '%I:%i %p, %e %M') as end_date"
            )
            ->where('events.id', $id)
            ->first();

        return response()->json($detail);
    }

    public function findEvents(Request $request)
    {
        // URL query 
        $lat = $request->query("lat");
        $lng = $request->query("lng");
        $keyword = $request->query("keyword");
        $cat = $request->query("cat"); // to filter events by category id EXPECTED VALUES (number) [1,2,3 .. ]
        $date = $request->query("date"); // to filter events by date EXPECTED VALUES (string) [week, month, year]
        $pop = $request->query("pop"); // to filter events by popular places id EXPECTED VALUES (number) [1,2,3 .. ]

        // base location (Tugu Jogja)
        $lat_val = $lat ?? -7.782916432596278;
        $lng_val = $lng ?? 110.36705274134874;
        $user_location = "ST_GeomFromText('POINT($lng_val $lat_val)', 4326)";

        // this query to get nearby events with user location
        $events = DB::table('events')
            ->join("categories", "categories.id", "=", "events.category_id")
            ->selectRaw(
                "events.location, 
                ST_DISTANCE_SPHERE(`position`,$user_location) AS distance, 
                Y(position) AS lat, X(position) AS lng, 
                ST_ASGEOJSON(position) as geometry, 
                GROUP_CONCAT(
                    JSON_OBJECT(
                              'id', events.id
                            , 'name', events.name
                            , 'start_date', DATE_FORMAT(events.start_date, '%e %M, %I:%i %p') 
                            , 'description', events.description
                            , 'photo', events.photo
                            , 'lat', ST_Y(events.position)
                            , 'lng', ST_X(events.position)
                    
                            , 'category_name', categories.name
                            , 'category_id', categories.id
                    
                            ) ORDER BY events.start_date ASC
                        ) AS events"
            )
            ->groupBy("position")
            ->whereNull('events.deleted_at')
            ->whereRaw("(select status from submitted_events se where se.event_id = events.id order by id desc limit 1) = 'verified' "); // only show verified events

        // set keyword filter
        if (isset($keyword)) {
            $events->where('events.name', 'like', "%$keyword%");
        }

        // set category filter
        if (isset($cat)) {
            $events->where('events.category_id', '=', $cat);
        }

        if (isset($pop)) {
            $events->where('events.popular_place_id', '=', $pop);
        }

        // set date filter
        if ($date == 'month') {
            $events->whereMonth('events.start_date', '=', Carbon::now()->month);
        } elseif ($date == 'year') {
            $events->whereYear('events.start_date', '=', Carbon::now()->year);
        } else { // default week
            $start_of_week = Carbon::now()->startOfWeek();
            $end_of_week = Carbon::now()->endOfWeek();
            $events->whereBetween('events.start_date', [$start_of_week, $end_of_week]);
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => collect($events->get())->map(function ($place) {
                return [
                    'type' => 'Feature',
                    'properties' => [
                        'lat' => $place->lat,
                        'lng' => $place->lng,
                        'distance' => $place->distance,
                        'location' => $place->location,
                        'events' => json_decode("[$place->events]") // Remember this, query result return collection of object {..},{..} and then wrap it with []array
                    ],
                    'geometry' => json_decode($place->geometry)
                ];
            })
        ]);
    }

 

 
    public function placeBoundaries()
    {
        $res = DB::select("SELECT name, ST_ASGEOJSON(polygon_area) AS polygon FROM place_boundaries");
        return response()->json([
            "type" => "FeatureCollection",
            "features" => collect($res)->map(function ($place) {
                return [
                    'type' => 'Feature',
                    'properties' => ['region' => $place->name],
                    'geometry' => json_decode($place->polygon),
                ];
            })
        ]);
    }

}
