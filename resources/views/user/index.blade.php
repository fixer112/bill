@extends('user.layout')
@section('title','Dashboard')
@section('content')
{{-- <div class="col-8 mx-auto"> --}}

<div class="row">
    <div class="col-md-4">
        <div class="card theme-bg round">
            <div class="card-header borderless">
                <h5 class="text-white">Wallet</h5>
            </div>
            <div class="card-block pt-0">
                <div class="earning-text mb-0">
                    <h3 class="mb-2 text-white f-w-300">{{currencyFormat(request()->user->balance)}}
                    </h3>
                    <span class="text-uppercase text-white d-block">Balance</span>
                </div>

                <div class="row mt-2">
                    <div class="col-6">
                        <div class="small"><a href="/user/wallet/{{request()->user->id}}/fund">Fund Wallet</a></div>
                    </div>
                    <div class="col-6">

                        <div class="small"><a href="/user/wallet/{{request()->user->id}}/history">History</a></div>

                    </div>
                </div>

            </div>


        </div>
    </div>
    <div class="col-md-4">
        <div class="card theme-inverse round">
            <div class="card-header borderless">
                <h5 class="text-white">Referral Wallet</h5>
            </div>
            <div class="card-block pt-0">
                <div class="earning-text mb-0">
                    <h3 class="mb-2 text-white f-w-300">{{currencyFormat(request()->user->referral_balance)}}
                    </h3>
                    <span class="text-uppercase text-white d-block">Balance</span>
                </div>
                <div class="row mt-2">
                    <div class="col-6">
                        <div class="small"><a href="/user/referral/{{request()->user->id}}/withdraw">Withdraw</a></div>
                    </div>
                    <div class="col-6">

                        <div class="small"><a href="/user/referral/{{request()->user->id}}/history">History</a></div>

                    </div>
                </div>

            </div>


        </div>
    </div>

    <div class="col-md-4">
        <div class="card">

            <div class="profile-userpic">
                <img src="{{request()->user->profilePic()}}" class="img-responsive" alt="">
            </div>


            <div class="profile-usertitle">
                <div class="profile-usertitle-name">
                    {{request()->user->full_name}}
                </div>
                <div class="profile-usertitle-job">
                    {{request()->user->type()}} Account
                </div>
            </div>

            <div class="m-2 small referral-link theme-bg p-2 rounded text-dark">
                <span> Referral Link : {{request()->user->getReferralLink()}}</span>
            </div>


            {{-- <div class="profile-userbuttons">
                <button type="button" class="btn btn-success btn-sm">Follow</button>
                <button type="button" class="btn btn-danger btn-sm">Message</button>
            </div> --}}




        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Recent Transactions</h5>

            </div>
            <div class="card-block px-0 py-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <th>Ref</th>
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Desc</th>
                        </thead>
                        <tbody>
                            @foreach (request()->user->transactions->take(10) as $transaction)
                            <tr>
                                <td>{{$transaction->ref}}</td>
                                <td>{{$transaction->amount}}</td>
                                <td>{{$transaction->balance}}</td>
                                <td>{{$transaction->type}}</td>
                                <td>{{$transaction->reason}}</td>
                                <td>{{$transaction->desc}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- </div> --}}
@endsection