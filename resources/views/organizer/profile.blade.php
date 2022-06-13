@section('title', 'organizer dashboard')
@extends('layouts.master')

@section('content')
    {{-- content --}}
    <div style="height: 100vh;" class="d-flex justify-content-center align-items-center flex-column">
        <div class="card" style="min-height: 400px;width: 800px">
            <div class="card-body d-flex justify-content-center align-items-center flex-column">
                <h3>{{ auth()->guard('organizer')->user()->name }}</h3>
                <br>
                <span>{{ auth()->guard('organizer')->user()->email }}</span>
                <span>{{ auth()->guard('organizer')->user()->address }}</span>
                <span class="text-muted">{{ auth()->guard('organizer')->user()->created_at }}</span>

                <form class="mt-4" action="/organizer/logout" method="POST">
                    @csrf
                    <input type="submit" class="btn btn-danger" value="Logout">
                </form>
            </div>
        </div>

    </div>



@endsection
