<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Place as PlaceResource;
use App\Models\Event;
use App\Models\SubmittedEvent;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminEventController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $events = null;
        $current_status = null;

        switch ($status) {
            case 'verified':
                $events = Event::with(['organizer', 'category', 'status'])->has('hasVerified')->get();
                $current_status = 'verified';
                break;

            case 'rejected':
                $events = Event::with(['organizer', 'category', 'status'])->has('hasRejected')->get();
                $current_status = 'rejected';
                break;

            case 'takedown':
                $events = Event::with(['organizer', 'category', 'status'])->has('hasTakedown')->get();
                $current_status = 'takedown';
                break;

            default:
                $events = Event::with(['organizer', 'category', 'status'])->has('status')->get();
                $current_status = 'all';
                break;
        }

        return view('admin.events', ['events' => $events, 'current_status' => $current_status]);
    }


    public function show(Event $event, $id)
    {
        return view('admin.event-detail', ['event' => Event::find($id)]);
    }


    public function acceptEvent(Request $request, $id)
    {
        $last_submission = SubmittedEvent::where('event_id', $id)->orderBy('created_at', 'desc')->first();
        $already_verified = $last_submission->status == 'verified' ? true : false;
        if ($already_verified) {
            return redirect()->back()->withErrors(['message' => 'This event already verified']);
        }
        SubmittedEvent::create([
            'event_id' => $id,
            'status' => 'verified',
        ]);
        return redirect()->route('admin.dashboard');
    }

    public function rejectEvent(Request $request, $id)
    {
        $last_submission = SubmittedEvent::where('event_id', $id)->orderBy('created_at', 'desc')->first();
        $already_verified = $last_submission->status == 'verified' ? true : false;
        if ($already_verified) {
            return redirect()->back()->withErrors(['message' => 'This event already verified']);
        }

        SubmittedEvent::create([
            'event_id' => $id,
            'status' => 'rejected',
            'reason' => $request->reason,
        ]);
        return redirect()->route('admin.dashboard');
    }
}
