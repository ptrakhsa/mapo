<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PopularPlaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        $user_location = "ST_GeomFromText('POINT(110.36705274134874 -7.782916432596278)', 4326)";

        //     $res = DB::select("SELECT GROUP_CONCAT(JSON_OBJECT(
        //         'name', name,
        //         'lat', Y(position),
        //         'lng', X(position),
        //         'start_date', DATE_FORMAT(start_date, '%e %b, %I:%i'),
        //         'distance', ST_Distance_Sphere(`position`, $user_location)
        //     ) ORDER BY start_date ASC )                        AS properties,
        //        ST_ASGEOJSON(position) as geometry
        // FROM `spatial_tables`
        // group by position");

        $res = DB::select("SELECT 'Jalan raya utama no 56'               as address,
                                    Y(position)            as lat,
                                    X(position)            as lng,
                                    ST_Distance_Sphere(`position`, $user_location) as distance,
                                    GROUP_CONCAT(
                                        json_object('name', name,
                                                    'start_date', DATE_FORMAT(start_date, '%e %b, %I:%i')
                                                    ) ORDER BY start_date ASC
                                                ) AS events,
                                    ST_ASGEOJSON(position) as geometry
 
                        FROM `spatial_tables`
                        GROUP BY position");

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => collect($res)->map(function ($place) {

                return [
                    'type' => 'Feature',
                    'properties' => [
                        'address' => $place->address,
                        'lat' => $place->lat,
                        'lng' => $place->lng,
                        'distance' => $place->distance,
                        'events' => json_decode("[$place->events]")
                    ],
                    'geometry' => json_decode($place->geometry)
                ];
            }),
        ]);
    }
}
