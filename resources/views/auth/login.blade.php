@extends('layouts.login')

@section('title', 'Login')

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
                    <p class="text-xs-center">LOGIN TO CONTINUE</p>
                    <form id="login-form" action="{{ route('login') }}" method="POST" novalidate="">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="username">E-Mail Address</label>
                            <input type="email" class="form-control underlined" name="email" id="email"
                                   value="{{ old('email') }}" placeholder="Your email address" required autofocus>
                            @if ($errors->has('email'))
                                <span class="has-error">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password">Password</label>
                            <input type="password" class="form-control underlined" name="password" id="password"
                                   placeholder="Your password" required>
                            @if ($errors->has('password'))
                                <span class="has-error">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="remember">
                                <input class="checkbox" id="remember"
                                       type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                <span>Remember me</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="forgot-btn pull-right">Forgot password?</a>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
