@section('title', 'Sorry')
@extends('layouts.master')

@section('content')
    <div class="d-flex justify-content-center flex-column align-items-center" style="height: 100vh;">
        <h1>401 Unauthenticated</h1>
        <a class="btn btn-primary" href="/organizer/register">go to register</a>
    </div>
@endsection