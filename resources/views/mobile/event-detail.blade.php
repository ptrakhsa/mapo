@section('title', 'Event Mapper')
@extends('layouts.master')


@section('head')

@endsection


@section('content')

    <style>
        h2 {
            color: black;
            margin: 20px 0px 10px 0px;
        }

        table {
            margin: 10px 0px 10px 0px;
        }

        .eo {
            color: blue;
        }

        .content {
            margin: 10px 0px 10px 0px;
        }

        .box {
            width: 100%;
            height: 70px;
            background-color: white;
        }
    </style>

    <div class="box">

        <i onclick="history.back()" class="fw fa fa-thin fa-angle-left fa-2x mx-3 my-3 "></i>


    </div>
    <div class=" bg-white">

        <img src="{{ $event->photo }}" class="img-fluid max-height:100%" alt="event image">

        <div class="card px-4 py-0 mx-3 my-0">
            <h2>{{ $event->name }}</h2>
            <h6>Created by
                <span class="eo"> {{ $event->organizer_name }}</span>
            </h6>
            <div class="row">
                <div class="content">
                    <span class="fa-fw select-all fas"></span>
                    <span>{{ $event->start_date }}</span><br>
                    <span class="fa-fw select-all fas"></span>
                    <span>{{ $event->location }}</span>
                    <!-- <div class="mx-2" style="background-color: rgb(212, 217, 221); width: 2px; height: 30px;"></div> -->
                </div>
                <p class="text-subtitle">{{ $event->description }}</p>
                <div class="mb-3">
                    {!! $event->content !!}
                </div>
            </div>
            <!-- <span class="badge bg-light-primary mb-3">{{ $event->category_name }}</span> -->
            <div class="card-footer d-flex justify-content-between ">
                <a target="_blank" href="{{ $event->link }}" class="btn btn-success rounded-pill"
                    style="width: 100%; position:static;">Open Route</a>
            </div>

        </div>
    </div>




@endsection
