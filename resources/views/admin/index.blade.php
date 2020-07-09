@extends('admin.layout')
@section('title','Dashboard')
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card theme-bg ">
            <div class="card-header borderless">
                <h5 class="text-white">Wallet</h5>
            </div>
            <div class="card-block pt-0">
                <div class="earning-text mb-0">
                    <h3 class="mb-2 text-white f-w-300">{{currencyFormat($users->sum('balance'))}}
                    </h3>
                    <span class="text-uppercase text-white d-block">Balance</span>
                </div>

                {{--  <div class="row mt-2">
                    <div class="col-6">
                        <div class="small"><a href="/user/wallet/{{request()->user->id}}/fund">Fund Wallet</a>
            </div>
        </div>
        <div class="col-6">

            <div class="small"><a href="/user/wallet/{{request()->user->id}}/history">History</a></div>

        </div>
    </div> --}}

</div>


</div>
</div>
<div class="col-md-4">
    <div class="card theme-bg2">
        <div class="card-header borderless">
            <h5 class="text-white">Referral Wallet</h5>
        </div>
        <div class="card-block pt-0">
            <div class="earning-text mb-0">
                <h3 class="mb-2 text-white f-w-300">{{currencyFormat($users->sum('referral_balance'))}}
                </h3>
                <span class="text-uppercase text-white d-block">Balance</span>
            </div>
            {{--  <div class="row mt-2">
                    <div class="col-6">
                        <div class="small"><a href="/user/referral/{{request()->user->id}}/withdraw">Withdraw</a>
        </div>
    </div>
    <div class="col-6">

        <div class="small"><a href="/user/referral/{{request()->user->id}}/history">History</a></div>

    </div>
</div> --}}

</div>


</div>
</div>


</div>

<div class="row">

    <div class="col-12">
        {{--  <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link show active" id="wallet" data-toggle="tab" href="#wallet" role="tab"
                    aria-controls="wallet" aria-selected="true">Recent Wallet History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link show" id="referral" data-toggle="tab" href="#referral" role="tab"
                    aria-controls="referral" aria-selected="true">Recent Referral History</a>
            </li>
        </ul> --}}

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#wallet" role="tab" data-toggle="tab" aria-selected="true">Recent
                    Wallet History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#referral" role="tab" data-toggle="tab">Recent Referral History</a>
            </li>

        </ul>

        <div class="tab-content card">

            <div role="tabpanel" class="tab-pane fade show active" id="wallet">

                <div class="card-block px-0 py-3">

                    <div class="table-responsive">
                        <table class="table table-hover ">
                            <thead>
                                <th>Ref</th>
                                <th>Username</th>
                                <th>User Package</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Profit</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Desc</th>
                                <th>Status</th>
                                <th>Plathform</th>
                                <th>Created At</th>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{$transaction->ref}}</td>
                                    <td class="font-weight-bold">
                                        @if ($transaction->user)
                                        <a href="/user/{{$transaction->user->id}}">
                                            {{$transaction->user->login}}</a>
                                        @else
                                        Guest
                                        @endif
                                    </td>
                                    <td>
                                        @if ($transaction->user)
                                        <a
                                            class="label rounded-pill text-white theme-bg">{{ucfirst($transaction->user->userPackage())}}</a>
                                        @else
                                        Guest
                                        @endif
                                    </td>
                                    <td>{{$transaction->amount}}</td>
                                    <td>{{$transaction->balance}}</td>
                                    <td>{{numberFormat($transaction->profit)}}</td>
                                    <td><a
                                            class="label rounded-pill text-white {{$transaction->type =='credit' ?'theme-bg':'theme-bg2'}}">{{$transaction->type}}</a>
                                    </td>
                                    <td>{{$transaction->reason}}</td>
                                    <td>{{$transaction->desc}}</td>
                                    <td><span class="badge badge-{{$transaction->statusColor()}} p-1 rounded-pill">
                                            {{ucfirst($transaction->status)}}
                                        </span>
                                    </td>
                                    <td>{{$transaction->plathform}}</td>
                                    <td>{{$transaction->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane show" id="referral">
                <div class="card-block px-0 py-3">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <th>Id</th>
                                <th>Username</th>
                                <th>User Package</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Desc</th>
                                <th>User Referred</th>
                                <th>Level</th>
                                <th>Created At</th>
                            </thead>
                            <tbody>
                                @foreach ($referrals as $transaction)
                                <tr>
                                    <td>{{$transaction->id}}</td>
                                    <td class="font-weight-bold"> <a href="/user/{{$transaction->user->id}}">
                                            {{$transaction->user->login}}</a>
                                    </td>
                                    <td>

                                        <a
                                            class="label rounded-pill text-white theme-bg">{{ucfirst($transaction->user->userPackage())}}</a>

                                    </td>
                                    <td>{{$transaction->amount}}</td>
                                    <td>{{$transaction->balance}}</td>
                                    <td>{{$transaction->desc}}</td>
                                    <td class="font-weight-bold"><a
                                            href="/user/{{$transaction->refered->id}}">{{$transaction->refered->login}}</a>
                                    </td>
                                    <td>{{$transaction->level}}</td>
                                    <td>{{$transaction->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection