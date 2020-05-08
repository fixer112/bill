@extends('auth.layout')
@section('title','Reset Password')
@section('content')
<p class="login-card-description">Reset Password</p>
<form action="{{ route('password.update') }}" method="POST">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
        <label for="usernmae" class="">Email</label>
        <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"
            required>
        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label for="password" class="">Password</label>
        <input type="password" name="password" id="password"
            class="form-control @error('username') is-invalid @enderror" placeholder="Password">
        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mb-4">
        <label for="password" class="">Confirm Password</label>
        <input type="password" name="password_confirmation"
            class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm Password">
        @error('password_confirmation')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <button id="login" class="btn btn-block login-btn mb-4" type="submit">Reset Password</button>
</form>
Go back to<a href="/login"> Login </a>
@endsection