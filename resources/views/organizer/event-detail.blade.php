@section('title', 'Event Detail')
@extends('layouts.master')


@section('head')

@endsection


@section('content')
{{-- header --}}
<div class="d-flex justify-content-between align-items-center px-3 py-3 bg-white">
    <div class="dropdown-toggle d-flex align-items-center dropdown" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer">
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
        <a class="btn" href="/organizer/dashboard">Dashboard</a>
        <a class="btn" href="/organizer/activity">Activity</a>
        <form action="/organizer/logout" method="POST">
            @csrf
            <input type="submit" class="btn" value="Logout">
        </form>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-9">
            <div class="card px-4 py-4 mx-3 my-3">
                <div class="card-content">
                    <h3>{{ $event->name }}</h3>
                    <span class="badge bg-light-primary mb-3">{{ $event->category_name }}</span>

                    <img src="{{ $event->photo }}" class="img-fluid w-100" alt="event image">
                    <div class="card-body">
                        <p class="text-subtitle">{{ $event->description }}</p>

                        <div class="mb-3">
                            {!! $event->content !!}
                        </div>
                        <small>
                            <span class="fa-fw select-all fas"></span>
                            <span>{{ date_format(date_create($event->start_date), 'H:i A, j F Y') }} -
                                {{ date_format(date_create($event->end_date), 'H:i A, j F Y') }}</span>
                        </small>
                        <br>
                        <small>
                            <span class="fa-fw select-all fas"></span>
                            <span>{{ $event->location }}</span>
                        </small>
                        <br>

                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <span>Held by <h5> {{ $event->organizer_name }}</h5></span>
                        <a target="_blank" href="{{ $event->link }}" class="btn btn-primary">Gmaps route</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3" style="position: relative;">
            <div class="card px-4 py-4 mx-3 my-3">
                <div class="card-content">
                    <h6 class="">Submission history</h6>
                    <ul>

                        @foreach ($submissions as $sub)
                        <li class="mb-3">
                            <strong>{{ $sub->status }}</strong>
                            <br>
                            <small>
                                <span class="fa-fw select-all fas"></span>
                                <span>{{ $sub->created_at ?? '-' }}</span>
                            </small>

                            <br>

                            @if ($sub->status == 'rejected' || $sub->status == 'takedown')
                            <small>
                                <span class="bi bi-file-earmark-medical-fill"></span>
                                <span style="color: rgb(205, 56, 56)">{{ $sub->reason ?? '-' }}</span>
                            </small>
                            @endif
                        </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection