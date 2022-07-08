@section('title', 'Organizer Login')
@extends('layouts.master')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div id="auth" class="card py-4 px-4" style="max-height: 400;width: 500;">


            <h1 class="auth-title">Log in.</h1>
            <p class="auth-subtitle mb-5">Login as Organizer</p>


            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/organizer/login">
                @csrf
                <div class="form-group position-relative has-icon-left mb-4">
                    <input name="email" type="email" required class="form-control" placeholder="Email"
                        value="{{ old('email') }}">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input name="password" required type="password" class="form-control" placeholder="Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>

                <button class="btn btn-primary btn-block shadow-lg mt-4">Log in</button>
                <div class="d-flex justify-content-center mt-2">
                    <a href="/organizer/register">create account</a>
                </div>
            </form>


        </div>
    </div>
