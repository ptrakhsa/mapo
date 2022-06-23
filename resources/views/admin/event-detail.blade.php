@section('title', 'Event Mapper')
@extends('layouts.admin-panel')


@section('head')

@endsection


@section('content')


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
                            <span>{{ $event->start_date }}</span>
                        </small>
                        <br>
                        <small>
                            <span class="fa-fw select-all fas"></span>
                            <span>{{ $event->location }}</span>
                        </small>
                        <br>

                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <span>{{ $event->organizer_name }}</span>
                        <a target="_blank" href="{{ $event->link }}" class="btn btn-primary">Gmaps route</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3" style="position: relative;">
            <div class="card px-4 py-4 mx-3 my-3" >
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
                                        <span style="color: rgb(205, 56, 56)">{{ $sub->reason ?? "-" }}</span>
                                    </small>
                                @endif
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>
    </div>


@endsection
