@section('title', 'Event Mapper')
@extends('layouts.master')


@section('head')

@endsection


@section('content')

    <div class="bg-white">
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

@endsection
