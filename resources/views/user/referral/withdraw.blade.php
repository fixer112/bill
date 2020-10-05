@extends('user.layout')
@section('title','Withraw Referral Wallet')

@section('content')
<div class="row">
    <div class="col-10 mx-auto card p-5">
        <h4 class="text-6 mb-4">Withdraw to Wallet ({{currencyFormat(request()->user->referral_balance)}})</h4>
        <form action="{{url()->current()}}" method="POST">
            @csrf

            <div class="form-group">
                <label>Amount</label>
                <div class="input-group">
                    <div class="input-group-prepend"> <span class="input-group-text">{{currencySymbol()}}</span>
                    </div>
                    <input name="amount" min="2000" type="number" step=".01"
                        class="form-control @error('amount') is-invalid @enderror" required placeholder="Enter Amount">
                </div>
                @error('amount')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
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