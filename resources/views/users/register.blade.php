@extends('layouts.login')

@section('title', 'User registration')

@section('content')
    <div class="auth">
        <div class="auth-container">
            <div class="card">
                <header class="auth-header">
                    <h1 class="auth-title">
                        <div class="logo">
                            <span class="l l1"></span>
                            <span class="l l2"></span>
                            <span class="l l3"></span>
                            <span class="l l4"></span>
                            <span class="l l5"></span>
                        </div>
                        {{ env('APP_NAME') }}
                    </h1>
                </header>
                <div class="auth-content">
                    <p class="text-xs-center">SIGNUP TO GET INSTANT ACCESS</p>
                    <form id="signup-form" action="{{ route('users.register', ['token' => $token]) }}" method="POST"
                          novalidate="novalidate">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') || $errors->has('surname') ? ' has-error' : '' }}">
                            <label for="name">Name</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" class="form-control underlined" name="name" id="name"
                                           value="{{ old('name') }}" placeholder="Enter first name"
                                           required="" aria-required="true">
                                    @if ($errors->has('name'))
                                        <span class="has-error">
                                            {{ $errors->first('name') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control underlined" name="surname" id="surname"
                                           value="{{ old('surname') }}" placeholder="Enter last name" required=""
                                           aria-required="true">
                                    @if ($errors->has('surname'))
                                        <span class="has-error">
                                            {{ $errors->first('surname') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control underlined" name="email" id="email"
                                   value="{{ $email }}" placeholder="Enter email address" disabled="disabled">
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password">Password</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="password" class="form-control underlined" name="password"
                                           id="password" placeholder="Enter password" required="" aria-required="true">
                                    @if ($errors->has('password'))
                                        <span class="has-error">
                                            {{ $errors->first('password') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control underlined" name="password_confirmation"
                                           id="password_confirmation" placeholder="Re-enter password" required=""
                                           aria-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">Sign Up</button>
                        </div>
                        <div class="form-group">
                            <p class="text-muted text-xs-center">
                                Already have an account?
                                <a href="{{ route('login') }}">Login!</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection