@section('title', 'organizer dashboard')
@extends('layouts.master')

@section('content')
    {{-- content --}}
    <div style="height: 100vh;" class="d-flex justify-content-center align-items-center flex-column">
        <div class="card" style="min-height: 400px;width: 800px">
            <div class="card-body d-flex justify-content-center align-items-center flex-column">
                <h3>Term of conditions</h3>
                <br>
                <ol>
                    <li>
                        After the event is submitted, the admin will review and then decide whether your event is accepted
                        or rejected.
                    </li>
                    <li> An event that has been verified can not be edited or removed.</li>
                    <li> Organizer can't submit an event which the same time and location as another verified event.</li>
                    <li>if your event is marked as abuse, the admin can takedown it.</li>
                </ol>
            </div>
        </div>

    </div>



@endsection
