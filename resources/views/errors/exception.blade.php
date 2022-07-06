@section('title', 'Sorry')
@extends('layouts.master')

@section('content')
    <div class="d-flex justify-content-center flex-column align-items-center" style="height: 100vh;">
        <div class="d-flex align-items-center" style="font-size: 40px;">
            <b>{{ $res['code'] }}</b> <span class="mx-3">|</span> {{ $res['message'] }}
        </div>

        @if ($res['action'])
            <a class="btn btn-primary mt-4" href="{{ $res['action']['url'] }}">{{ $res['action']['text'] }}</a>
        @endif

    </div>
@endsection
