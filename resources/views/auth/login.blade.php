@extends('auth.layout')
@section('title','Login')
@section('content')
<p class="login-card-description">Sign into your account</p>
<form action="{{ route('login') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="usernmae" class="sr-only">Username</label>
        <input type="text" name="login" id="username" class="form-control @error('login') is-invalid @enderror"
            placeholder="Username" required>
        @error('login')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label for="password" class="sr-only">Password</label>
        <input type="password" name="password" id="password"
            class="form-control @error('username') is-invalid @enderror" placeholder="Password">
        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mb-4">
        <div class="">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
        </div>
    </div>

    <button id="login" class="btn btn-block login-btn mb-4" type="submit">Login</button>
</form>
<a href="{{ route('password.request') }}" class="forgot-password-link">Forgot
    password?</a> | Go back <a href="/"> Home </a>
<p class="login-card-footer-text">Dont
    have an account? <a href="/register" class="text-reset">Register here</a></p>
@endsection