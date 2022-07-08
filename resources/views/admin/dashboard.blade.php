@section('title', 'Dashobard')
@extends('layouts.admin-panel')
@section('head')
    <style>
        .btn:focus,
        .btn:active {
            outline: none !important;
            box-shadow: none;
        }

        .date-label {
            position: absolute;
            top: 0px;
            left: 0px;
            background-color: rgb(189, 72, 72);
            color: white;
            font-weight: bold;
            padding-left: 10px;
            padding-right: 10px;
            border-bottom-right-radius: 10px;

        }
    </style>
@endsection
@section('content')
    <div class="page-heading">

        {{-- PAGE DESCRIPTION --}}
        {{--  --}}
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Dashboard</h3>
                    <p class="text-subtitle text-muted">Incoming and upcoming events</p>
                    <div class="mb-5 d-flex">

                        <a href="/admin/dashboard?status=incoming"
                            class="btn btn-sm rounded-pill {{ $current_status == 'incoming' ? 'btn-primary' : 'btn-outline-primary' }}">Incoming</a>
                        <a href="/admin/dashboard?status=upcoming"
                            class="mx-1 btn btn-sm rounded-pill {{ $current_status == 'upcoming' ? 'btn-primary' : 'btn-outline-primary' }}">Upcoming</a>


                        {{-- upcoming options --}}

                        @if ($current_status == 'upcoming')
                            {{-- separator --}}
                            <div style="background-color: rgb(212, 217, 221); width: 2px;height: 30px" class="mx-2">
                            </div>
                            {{-- end separator --}}

                            <a href="/admin/dashboard?status=upcoming&date=week"
                                class="{{ $current_date == 'week' ? 'btn-success' : 'btn-outline-success' }} btn btn-sm rounded-pill">This
                                week</a>
                            <a href="/admin/dashboard?status=upcoming&date=month"
                                class="{{ $current_date == 'month' ? 'btn-success' : 'btn-outline-success' }} btn btn-sm mx-1 rounded-pill">This
                                month</a>
                            <a href="/admin/dashboard?status=upcoming&date=year"
                                class="{{ $current_date == 'year' ? 'btn-success' : 'btn-outline-success' }} btn btn-sm rounded-pill">This
                                year</a>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">unapproved</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        {{--  --}}
        {{-- END PAGE DESCRIPTION --}}



        {{-- DASHBOARD EVENT LIST --}}
        <section id="content-types">
            <div class="row">
                @forelse ($current_submitted_events as $event)
                    <div class="col-xl-4 col-md-6 col-sm-12">
                        <div class="card">

                            <div class="card-content">
                                <div class="date-label">
                                    {{ \Carbon\Carbon::parse($event->start_date)->diffForHumans() }}</div>
                                <img style="max-height: 400px;object-fit: contain;" class="img-fluid w-100"
                                    src="{{ $event->photo }}" alt="Card image cap">
                                <div class="card-body">
                                    <h4 class="card-title">{{ $event->name }}</h4>
                                    <span class="badge bg-light-primary mb-3">{{ $event->category->name }}</span>
                                    <p class="card-text truncate-threeline">
                                        {{ $event->description }}
                                    </p>
                                    <p class="card-text">
                                        <small>
                                            <span class="fa-fw select-all fas"></span>
                                            {{ date_format(date_create($event->start_date), 'H:i A, j F Y') }}
                                        </small>
                                        <br>
                                        <small>
                                            <span class="fa-fw select-all fas"></span>
                                            {{ $event->location }}
                                        </small>
                                    </p>


                                </div>

                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <span>{{ $event->organizer->name }}</span>
                                <a href="/admin/event/detail/{{ $event->id }}" class="btn btn-light-primary">Read
                                    More</a>
                            </div>
                        </div>

                    </div>
                @empty
                    <div>Waiting for something new ..</div>
                @endforelse
            </div>
        </section>
        {{-- END DASHBOARD EVENT LIST --}}

    </div>

    <script>
        function messageNotifier(message) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "bottom",
                position: "center",
                close: true,
                backgroundColor: '#4F6467',
            }).showToast();
        }

        // note render 'false' is equal with false in js 
        let message = {{ session()->has('message') ? Illuminate\Support\Js::from(session()->get('message')) : 'false' }};
        if (message) {
            messageNotifier(message)
        }
    </script>

@endsection
