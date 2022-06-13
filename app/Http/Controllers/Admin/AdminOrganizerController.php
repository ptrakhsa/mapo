<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\EventOrganizer;
use App\Models\Organizer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminOrganizerController extends Controller
{
    public function index()
    {
        return view('admin.eo', [
            'data' => Organizer::all(),
        ]);
    }

    public function events(Request $request, $id)
    {
        $events = Event::where('organizer_id', $id)->with(['status'])->get();
        return response()->json($events);
    }
}
