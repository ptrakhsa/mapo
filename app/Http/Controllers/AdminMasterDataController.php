<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMasterDataController extends Controller
{
    public function eventCategories()
    {
        $categories = DB::table("submitted_events")
            ->join('events', 'submitted_events.event_id', '=', 'events.id')
            ->join('categories', 'events.category_id', '=', 'categories.id')
            ->groupBy('categories.id')
            ->where('submitted_events.status', 'verified')
            ->orWhere('submitted_events.status', 'done')
            ->selectRaw('categories.name, COUNT(events.id) AS count')
            ->get();
            

        return view('admin.categories', compact('categories'));
    }

    public function placeBoundaries()
    {
        return view('admin.place-boundaries');
    }
}
