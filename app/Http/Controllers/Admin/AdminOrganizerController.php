<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\EventOrganizer;
use App\Models\Organizer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminOrganizerController extends Controller
{
    public function organizerEvents(Request $request)
    {
        $recent_status_query = $request->query('status');
        $organizer = Organizer::with(['events.status'])->findOrFail($request->id);
        $event_statuses = collect($organizer->events)->countBy('status.status')->toArray();

        if (in_array($recent_status_query, ['rejected', 'verified', 'waiting', 'takedown', 'done'])) {
            $events_by_status = collect($organizer->events)->where('status.status', $recent_status_query)->all();
        } else {
            $events_by_status = collect($organizer->events)->all();
        }

        return view('admin.organizer-with-events', compact('organizer', 'event_statuses', 'recent_status_query', 'events_by_status'));
    }


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
