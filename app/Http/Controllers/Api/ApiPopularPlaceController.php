<?php

namespace App\Http\Controllers\Api;

use App\Models\PopularPlaces;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiPopularPlaceController extends Controller
{
    public function index()
    {
        $popular_places = PopularPlaces::all()->take(3);
        return response()->json($popular_places);
    }
}
