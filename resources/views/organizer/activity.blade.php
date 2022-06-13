@section('title', 'organizer dashboard')
@extends('layouts.master')

@section('content')
    {{-- header --}}
    <div class="d-flex justify-content-between align-items-center px-3 py-3 bg-white">
        <div class="dropdown-toggle d-flex align-items-center dropdown" id="dropdownMenuButton" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" style="cursor: pointer">
            <div class="avatar bg-info avatar-lg bg-warning me-3">
                <img src="/assets/images/faces/1.jpg" alt="" srcset="">
            </div>
            <h4>{{ auth()->guard('organizer')->user()->name }}</h4>
        </div>
        {{-- hidden --}}
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="/organizer/profile">Profile</a>
            <a class="dropdown-item" href="/organizer/toc">TOC</a>
        </div>


        <div class="d-flex align-content-center">
            <a class="btn" href="/organizer/activity">activity</a>
            <form action="/organizer/logout" method="POST">
                @csrf
                <input type="submit" class="btn" value="Logout">
            </form>
        </div>
    </div>


    {{-- content --}}
    <div class="container mt-4">
        Your activity
        <div class="d-flex justify-content-center row mt-4">

            @forelse ($activities as $activity)
                <div class="card pa">
                    <div class="media">
                        <div class="media-body py-3 px-3">
                            <h5 class="mt-0">{{ $activity->status }}</h5>
                            <small class="text-muted">3 juni 2020</small>
                            <br>
                            <br>

                            <span>
                                @if ($activity->status == 'waiting')
                                    Admin still review <strong>{{ $activity->event_name }}</strong> event
                                @endif

                                @if ($activity->status == 'rejected')
                                    Admin rejected your <strong>{{ $activity->event_name }}</strong> event because
                                    <strong>{{ $activity->reason }}</strong>
                                @endif

                                @if ($activity->status == 'verified')
                                    Congrats <strong>{{ $activity->event_name }}</strong> has been verified
                                @endif

                                @if ($activity->status == 'takedown')
                                    Sorry <strong>{{ $activity->event_name }}</strong> has been takedown by admin because
                                    <strong>{{ $activity->reason }}</strong>
                                @endif

                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <span class="text-muted text-center mt-4">no activities</span>
            @endforelse

        </div>
    </div>
@endsection
