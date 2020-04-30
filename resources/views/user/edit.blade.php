@extends(request()->user->is_admin ?'admin.layout':'user.layout')
@section('title','Edit Profile')
@php
$admin = request()->user->is_admin;
@endphp

@section('content')
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <img class="profile-userpic" src="{{request()->user->profilePic()}}" />
        </div>
        <form method="POST" action="{{url()->current()}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Username</label>
                        <div class="input-group">

                            <input type="text" class="form-control" disabled value="{{request()->user->login}}">

                        </div>

                    </div>

                    <div class="form-group">
                        <label>First Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                name="first_name" value="{{request()->user->first_name}}" required>
                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                name="last_name" value="{{request()->user->last_name}}" required>
                            @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-group">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{request()->user->email}}" {{!$admin ? 'required' :''}}>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('number') is-invalid @enderror" name="number"
                                value="{{request()->user->number}}" {{!$admin ? 'required' :''}}>
                            @error('number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Profile Picture</label>
                        <div class="input-group">
                            <input type="file" accept="image/*" class="form-control @error('pic') is-invalid @enderror"
                                name="pic">
                            @error('number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @if (!Auth::user()->is_admin)
                    <div class="form-group">
                        <label>Old Password</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('old_password') is-invalid @enderror"
                                name="old_password">
                            @error('old_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>New Password</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('password') is-invalid @enderror"
                                name="password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('password') is-invalid @enderror"
                                name="password_confirmation">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection