@extends('user.layout')
@section('title','Debit Wallet')
@section('content')
<div class="row">
    <div class="col-10 mx-auto card p-5">
        <h4 class="text-6 mb-4">Debit Wallet by Admin</h4>
        <form method="POST" accept="{{url()->current()}}">
            @csrf
            <div class="form-group">
                <label>Amount</label>
                <div class="input-group">
                    <div class="input-group-prepend"> <span class="input-group-text">{{currencySymbol()}}</span>
                    </div>
                    <input name="amount" type="number" step=".01"
                        class="form-control @error('amount') is-invalid @enderror" required placeholder="Enter Amount">
                    @error('amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <div class="input-group">

                    <textarea name="desc" class="form-control @error('desc') is-invalid @enderror"
                        required>Charges for previous transaction(s)</textarea>
                    @error('desc')
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