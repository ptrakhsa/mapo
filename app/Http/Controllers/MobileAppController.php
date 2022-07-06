<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobileAppController extends Controller
{
    public function detail(Request $request)
    {
        $event = DB::table('events')->where('events.id', $request->id);
        $is_exists = $event->exists();
        if ($is_exists) {
            $event = DB::table('events')
                ->select('events.id', 'events.name', 'events.description', 'events.content', 'events.start_date', 'events.end_date', 'events.location', 'events.photo', 'events.link')
                ->addSelect('categories.name AS category_name', 'organizers.name AS organizer_name')
                ->join('categories', 'categories.id', '=', 'events.category_id')
                ->join('organizers', 'organizers.id', '=', 'events.organizer_id')
                ->where('events.id', $request->id)
                ->first();

            return view('mobile.event-detail', compact('event'));
        } else {
            $res = [
                'message' => 'Event not found',
                'code' => 404,
                'action' => [
                    'text' => 'back',
                    'url' => route('mobile.find-events')
                ]
            ];
            return view('errors.exception', compact('res'));
        }
    }
}
