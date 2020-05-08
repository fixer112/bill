@extends('auth.layout')
@section('title','Reset Password')
@section('content')
<p class="login-card-description">Reset Password</p>
<form action="{{ route('password.email') }}" method="POST">
    @csrf
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

    <button id="login" class="btn btn-block login-btn mb-4" type="submit">Send Password Reset Link</button>
</form>
Go back to<a href="/login"> Login </a>

@endsection