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

            case 'done':
                $events = Event::with(['organizer', 'category', 'status'])->has('hasDone')->get();
                $current_status = 'done';
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
        return view('admin.event-detail-action', ['event' => Event::findOrFail($id)]); 
    }

    public function showEventDetail(Request $request)
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

            $submissions = DB::table('submitted_events')->selectRaw('id, status, reason, created_at')->where('event_id', $request->id)->orderBy('created_at', 'desc')->get();

            return view('admin.event-detail', compact('event', 'submissions'));
        } else {
            $res = [
                'message' => 'Event not found',
                'code' => 404,
                'action' => [
                    'text' => 'back',
                    'url' => route('admin.events')
                ]
            ];
            return view('errors.exception', compact('res'));
        }
    }


    public function acceptEvent(Request $request, $id)
    {
        $last_submission = SubmittedEvent::where('event_id', $id)->orderBy('id', 'desc')->first();
        $in_waiting = $last_submission->status == 'waiting' ? true : false;
        if ($in_waiting) {
            SubmittedEvent::create([
                'event_id' => $id,
                'status' => 'verified',
            ]);
            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->withErrors(['message' => 'to accept, event must be in waiting']);
    }

    public function markAsDoneEvent(Request $request, $id)
    {
        $last_submission = SubmittedEvent::where('event_id', $id)->orderBy('id', 'desc')->first();
        $has_verified = $last_submission->status == 'verified' ? true : false;
        if ($has_verified) {
            SubmittedEvent::create([
                'event_id' => $id,
                'status' => 'done',
            ]);
            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->withErrors(['message' => 'to mark as done, event must be verified']);
    }

    public function takedownEvent(Request $request, $id)
    {
        $last_submission = SubmittedEvent::where('event_id', $id)->orderBy('id', 'desc')->first();
        $has_verified = $last_submission->status == 'verified' ? true : false;
        if ($has_verified) {
            SubmittedEvent::create([
                'event_id' => $id,
                'status' => 'takedown',
                'reason' => $request->reason,
            ]);
            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->withErrors(['message' => 'to takedown, event must be verified']);
    }

    public function rejectEvent(Request $request, $id)
    {
        $last_submission = SubmittedEvent::where('event_id', $id)->orderBy('id', 'desc')->first();
        $in_waiting = $last_submission->status == 'waiting' ? true : false;
        if ($in_waiting) {
            SubmittedEvent::create([
                'event_id' => $id,
                'status' => 'rejected',
                'reason' => $request->reason,
            ]);
            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->withErrors(['message' => 'to reject, event must be in waiting']);
    }
}
