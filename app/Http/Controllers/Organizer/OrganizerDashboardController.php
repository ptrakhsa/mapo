<?php

namespace App\Http\Controllers\Organizer;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class OrganizerDashboardController extends Controller
{
    public function index()
    {
        $organizer_id = Auth::guard('organizer')->user()->id;
        $events = Event::with(['submissions'])->has('status')->where('organizer_id', $organizer_id)->orderBy('created_at', 'desc')->get();


        return view('organizer.dashboard', ['my_events' => $events]);
    }
}
