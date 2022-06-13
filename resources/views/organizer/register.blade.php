@section('title', 'organizer reg')
@extends('layouts.master')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div id="auth" class="card py-4 px-4" style="max-height: 800;width: 500;">


            <h1 class="auth-title">Register</h1>
            <p class="auth-subtitle mb-5">Sign up as Organizer</p>

            <form action="/organizer/register" method="POST">
                @csrf
                <div class="form-group mb-4">
                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Name" required>
                    @error('name')
                        <div class="invalid-feedback">
                            <i class="bx bx-radio-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <input name="email" type="email" required class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email">
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="bx bx-radio-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                <div class="form-group mb-4">
                    <textarea name="address" required type="text" class="form-control @error('address') is-invalid @enderror"
                        placeholder="Address"></textarea>
                    @error('address')
                        <div class="invalid-feedback">
                            <i class="bx bx-radio-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <input name="password" required type="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="bx bx-radio-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <input class="btn btn-primary btn-block shadow-lg mt-4" value="Register" type="submit" />
                <div class="d-flex justify-content-center mt-2">
                    <a href="/organizer/login">i already have an account</a>
                </div>
            </form>


        </div>
    </div>
