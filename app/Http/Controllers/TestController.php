<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PopularPlaces;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        // return Event::with('inPopularPlaces')->get();
        return PopularPlaces::with('events')->get();
    }
}
