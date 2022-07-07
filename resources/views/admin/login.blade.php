@section('title', 'admin login')
@extends('layouts.master')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div>

        <div id="auth" class="card py-4 px-4" style="max-height: 550;max-width: 500;">
            <img src="/assets/images/logo/logo-2.png" alt="Logo" srcset="" style=" margin:50px 0px 50px 35%; width: 30%">

            <h3 class="auth-title mb-1">Log in</h3>
            <p class="auth-subtitle mb-4">Log as <span>Administrator</span></p>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form method="POST" action="/admin/login">
                @csrf
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="email" name="email" class="form-control" placeholder="Username">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input name="password" type="password" class="form-control" placeholder="Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>

                <button class="btn btn-primary btn-block shadow-lg mt-4">Log in</button>
            </form>


        </div>
    </div>
</div>