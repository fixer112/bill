@extends('user.layout')
@section('title','Transfer')
@section('content')


<div class="row" id="fund">
    <div class="col-10 mx-auto card p-5">
        <h4 class="text-6 mb-4">Transfer to wallet</h4>
        <form method="POST" accept="{{url()->current()}}">
            @csrf
            <div class="form-group">
                <label>Username</label>
                <div class="input-group">
                    <input name="username" type="type" class="form-control @error('username') is-invalid @enderror"
                        required placeholder="Username">
                    @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

            </div>
            <div class="form-group">
                <label>Amount</label>
                <div class="input-group">
                    <div class="input-group-prepend"> <span class="input-group-text">{{currencySymbol()}}</span>
                    </div>
                    <input name="amount" type="number" step=".01" min="100"
                        class="form-control @error('amount') is-invalid @enderror" required placeholder="Enter Amount">
                    @error('amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <div class="input-group">
                    <input class="form-control @error('password') is-invalid @enderror" name="password" type="password"
                        placeholder="Confirm your password to continue" required>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <button class="btn btn-primary btn-block" type="submit">Continue</button>
        </form>
    </div>
</div>
@endsection