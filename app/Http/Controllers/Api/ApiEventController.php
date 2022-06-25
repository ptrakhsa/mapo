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
    public function index(Request $request)
    {
        // lat lng filter
        $lat = $request->query('lat');
        $lng = $request->query('lng');
        $distance_radius_query = '';
        $distance_query = '';
        if ($lat && $lng) {
            $distance_query = ",(6371 * acos(cos(radians($lat)) * cos(radians(lat)) * cos(radians(lng) - radians($lng)) + sin(radians($lat)) * sin(radians(lat)))) AS distance";
            $distance_radius_query = 'HAVING distance < 2 ORDER BY distance';
        }

        // abaikan dulu
        // $popular_place = $request->query('popular_place') ?? 'IS NOT NULL';

        // keyword filter
        $keyword_query = $request->query('keyword') ? 'AND e.name like "%' . $request->query('keyword') . '%"' : '';

        // category filter
        $category_query = $request->query('cat') ? 'AND e.category_id = ' . $request->query('cat') : '';

        // date filter
        $date = $request->query('date');
        $date_query = '';
        if ($date == 'month') {
            $date_query = 'AND month(e.start_date) = month(now())';
        } elseif ($date == 'year') {
            $date_query = 'AND year(e.start_date) = year(now())';
        } else { // default week
            $start_of_week = Carbon::now()->startOfWeek();
            $end_of_week = Carbon::now()->endOfWeek();
            $date_query = "AND e.start_date BETWEEN '$start_of_week' AND '$end_of_week'";
        }



        $native_query = "SELECT 
        e.id, e.name, e.description, e.start_date, e.location, e.lat, e.lng, e.photo, 
        c.name as category_name, e.category_id,
        se.status
        $distance_query
         FROM events e
            inner join submitted_events se on e.id = se.event_id
            inner join categories c on c.id = e.category_id
         where se.status = 'verified' 
         $category_query
         $date_query
         $keyword_query
         GROUP BY e.id
         $distance_radius_query
         ";

        //  return $native_query;

        $places = DB::select($native_query);

        // return response()->json([
        //     'count' => count($places),
        //     'query' => [
        //         [
        //             'lat' => $lat,
        //             'lng' => $lng,
        //             'keyword_query' => $keyword_query,
        //             // 'popular_place' => $popular_place,
        //             'cat' => $category_query,
        //             'date_query' => $date_query,
        //         ]
        //     ],
        //     'data' => $places
        // ]);

        $geoJSONdata = collect($places)->map(function ($place) {
            return [
                'type' => 'Feature',
                'properties' => [
                    'id' => $place->id,
                    'name' => $place->name,
                    'description' => $place->description,
                    'start_date' => date_format(date_create($place->start_date), "H:i, j F Y"),
                    'location' => $place->location,
                    'lat' => $place->lat,
                    'lng' => $place->lng,
                    'photo' => $place->photo,
                    'category_name' => $place->category_name,
                    'category_id' => $place->category_id,
                    'status' => $place->status,
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        $place->lng,
                        $place->lat,
                    ],
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $geoJSONdata,
        ]);
    }

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
                 ,DATE_FORMAT(events.start_date, '%I:%i, %e %b') as start_date
                 ,DATE_FORMAT(events.end_date, '%I:%i, %e %b') as end_date"
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
                            , 'start_date', DATE_FORMAT(events.start_date, '%e %b, %I:%i')
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

    public function getEventsByOrganizer(Request $request, $id)
    {
        $events = DB::table('events')
            ->addSelect('id', 'name', 'description')
            ->selectRaw('(select status from submitted_events se where se.event_id = events.id order by id desc limit 1) as status')
            ->where('organizer_id', $request->id)
            ->get();

        return response()->json($events);
    }


    public function eventDetail($id)
    {
        return Event::with(['organizer', 'category'])->find($id);
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

    public function yogyakartaGeoJSON()
    {
        // $yogyaStr = file_get_contents(storage_path() . '/app/public/geojson/yogyakarta-province.geojson');
        // $yogyaJson = json_decode($yogyaStr, true);
        // return response()->json($yogyaJson);

        $res = DB::select("SELECT name, ST_ASGEOJSON(polygon_area) AS polygon FROM place_boundaries");
        // kene1
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
            array_push($errors, ['start_date' => 'event can not more than one week']);
            return response()->json($errors, 422);
        }


        // event must inside jogja 
        $not_in_popular_place = $request->popular_place_id == null ? true : false;
        if ($not_in_popular_place) {
            $lat = $request->lat;
            $lng = $request->lng;
            $event_as_point_text = "POINT($lng $lat)";
            // kene2
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
        return SubmittedEvent::where('event_id', $id)->orderBy('created_at', 'desc')->get();
    }
}
