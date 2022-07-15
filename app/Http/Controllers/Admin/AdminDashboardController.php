<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $date = $request->query('date');

        $current_submitted_events = null;
        $current_status = null;
        $current_date = null;

        switch ($status) {
            case 'upcoming':
                $current_status = 'upcoming';

                if ($date == 'month') {
                    $current_date = 'month';
                    $current_submitted_events = Event::with(['organizer', 'category', 'status'])->has('hasVerified')->whereMonth('start_date', date('m'))->get();
                } elseif ($date == 'year') {
                    $current_date = 'year';
                    $current_submitted_events = Event::with(['organizer', 'category', 'status'])->has('hasVerified')->whereYear('start_date', date("Y"))->get();
                } else {
                    $current_date = 'week';
                    $current_submitted_events = Event::with(['organizer', 'category', 'status'])->has('hasVerified')->whereBetween('start_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
                }



                break;

            default:
                $current_submitted_events = Event::with(['organizer', 'category', 'status'])->has('inWaiting')->get(); //query select ['organizer', 'category', 'status']
                $current_status = 'incoming';

                break;
        }

        return view('admin.dashboard', [
            'current_submitted_events' => $current_submitted_events,
            'current_status' => $current_status,
            'current_date' => $current_date,
        ]);
    }
}
