@extends('auth.layout')
@section('title','Register')
@section('content')
<p class="login-card-description">Register an account with us</p>
<form action="{{ route('register') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="usernmae" class="">Username</label>
        <input type="text" name="login" id="username" class="form-control @error('login') is-invalid @enderror"
            placeholder="Username" value="{{old('login')}}" required>
        @error('login')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="email" class="">Email</label>
        <input type="email" name="email" id="email" value="{{old('email')}}"
            class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" required>
        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <label class="">First Name</label>
        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
            placeholder="First Name" value="{{old('first_name')}}" required>
        @error('first_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <label class="">Last Name</label>
        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
            placeholder="Last Name" value="{{old('last_name')}}" required>
        @error('last_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <label class="">Mobile Number</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">+234</span>
            </div>
            <input type="text" name="number" class="form-control @error('number') is-invalid @enderror"
                placeholder="Mobile number" value="{{old('number')}}" required>
            @error('number')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label class="">Gender</label>
        <select name="gender" class="custom-select @error('gender') is-invalid @enderror" required>
            <option value="">Choose Type</option>
            <option value="male" {{old('gender') == 'male' ? 'selected' :''}}>Male</option>
            <option value="female" {{old('gender') == 'female' ? 'selected' :''}}>Female</option>

        </select>
        @error('gender')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <label class="">Address</label>
        <div class="input-group">

            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                placeholder="Address" value="{{old('address')}}" required>
            @error('address')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>


    <div class="form-group">
        <label class="">User Type</label>
        <select name="reseller" class="custom-select @error('reseller') is-invalid @enderror" required>
            <option value="">Choose Type</option>
            <option value="0" {{old('reseller') == '0' ? 'selected' :''}}>Individual</option>
            <option value="1" {{old('reseller') == '1' ? 'selected' :''}}>Reseller</option>

        </select>
        @error('reseller')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>



    <div class="form-group mb-4">
        <label for="password" class="">Password</label>
        <input type="password" name="password" id="password"
            class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mb-4">
        <label for="password" class="">Password Confirmation</label>
        <input type="password" name="password_confirmation" id="password"
            class="form-control @error('password') is-invalid @enderror" placeholder="Confirm Password" required>
        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>


    <button id="login" class="btn btn-block login-btn mb-4" type="submit">Register</button>
</form>
Go back <a href="/"> Home </a>
<p class="login-card-footer-text">Already
    have an account? <a href="/login" class="text-reset">Login here</a></p>
@endsection