@extends('admin.layout')

@section('title','Create Admin')

@section('content')
<div class="card">
    <div class="card-body">

        <form method="POST" action="{{url()->current()}}" enctype="multipart/form-data" id="role">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Username</label>
                        <div class="input-group">

                            <input type="text" class="form-control @error('login') is-invalid @enderror" required
                                name="login" value="{{old('login')}}">
                            @error('login')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>

                    </div>

                    <div class="form-group">
                        <label>First Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                name="first_name" value="{{old('first_name')}}" required>
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
                                name="last_name" value="{{old('last_name')}}" required>
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
                                value="{{old('email')}}" required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>

                    <div class="form-group">
                        <label>Default Role</label>
                        <div class="input-group">
                            <select type="role" class="custom-select @error('role') is-invalid @enderror" name="role"
                                required>
                                <option>Choose Role</option>
                                @foreach (Spatie\Permission\Models\Role::all() as $role)
                                <option value="{{$role->id}}" {{$role->id == old('role') ? 'selected':''}}
                                    {{$role->id == 1 ? 'disabled':''}}>
                                    {{ucwords($role->name)}}</option>
                                @endforeach
                            </select>
                            @error('role')
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
                            <div class="input-group-prepend">
                                <span class="input-group-text">+234</span>
                            </div>
                            <input type="text" class="form-control @error('number') is-invalid @enderror" name="number"
                                value="{{old('number')}}" required>
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
                            @error('pic')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group">
                        <label>New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
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
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
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
            <button class="btn btn-primary">Create</button>
        </form>
    </div>
</div>

@endsection