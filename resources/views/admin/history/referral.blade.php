@extends('admin.layout')
@section('title','Referral History')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card theme-inverse round">
            <div class="card-header borderless">
                <h5 class="text-white">Referral Wallet</h5>
            </div>
            <div class="card-block pt-0">
                <div class="earning-text mb-0">
                    <h3 class="mb-2 text-white f-w-300">{{currencyFormat($users->sum('referral_balance'))}}
                    </h3>
                    <span class="text-uppercase text-white d-block">Balance</span>
                </div>


            </div>


        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col">
                        <i class="fa fa-money-bill f-30 text-c-green"></i>
                        <h6 class="m-t-50 m-b-0">Total Referral Transactions</h6>
                    </div>
                    <div class="col text-right">
                        <h3 class="text-c-green f-w-300">{{currencyFormat($referrals->sum('amount'))}}</h3>
                        <span class="text-muted d-block">Total Refferal Amount </span>
                        <span class="badge theme-bg text-white m-t-20">{{wholeNumberFormat($referrals->count())}}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col">
                        <i class="fa fa-money-bill f-30 text-c-green"></i>
                        <h6 class="m-t-50 m-b-0"> Referral Transactions</h6>
                    </div>
                    <div class="col text-right">
                        <h3 class="text-c-green f-w-300">{{currencyFormat($transactions->sum('amount'))}}
                        </h3>
                        <span class="text-muted d-block"> Refferal Amount </span>
                        <span
                            class="badge theme-bg text-white m-t-20">{{wholeNumberFormat($transactions->count())}}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="">
                    <h5>History</h5>
                </div>
                <div class="">
                    <form class="form-inline" action="{{url()->current()}}">

                        <div class="input-group input-group-sm my-1 mr-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">From</span>
                            </div>
                            <input type="date" name="from" value="{{$from->format('Y-m-d')}}" class="form-control"
                                aria-label="Small">
                        </div>
                        <div class="input-group input-group-sm my-1 mr-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">To</span>
                            </div>
                            <input type="date" name="to" value="{{$to->format('Y-m-d')}}" class="form-control"
                                aria-label="Small">
                        </div>
                        <div class="input-group input-group-sm my-1 mr-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">User Types</span>
                            </div>
                            <select class="custom-select" name="sub_type" aria-label="Small">
                                <option value="">All</option>
                                @foreach ($sub_types as $sub)
                                <option value="{{$sub}}" {{$sub == $sub_type ? 'selected': ''}}>{{ucfirst($sub)}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group input-group-sm my-1 mr-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Desc</span>
                            </div>
                            <input type="text" name="ref" placeholder="Search by description" value="{{$desc}}"
                                class="form-control" aria-label="Small">
                        </div>
                        <div class="input-group input-group-sm my-1 mr-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Ref</span>
                            </div>
                            <input type="text" name="ref" placeholder="Search by ref" value="{{$ref}}"
                                class="form-control" aria-label="Small">
                        </div>

                        <div class="input-group input-group-sm my-1 mr-1">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-block px-0 py-3">
                <div class="table-responsive">
                    <table class="table table-hover ">
                        <thead>
                            <th>Ref</th>
                            <th>Username</th>
                            <th>User Package</th>
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Desc</th>
                            <th>Referred User</th>
                            <th>Level</th>
                            <th>Created At</th>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{$transaction->ref}}</td>
                                <td class="font-weight-bold">
                                    <a href="/user/{{$transaction->user->id}}">
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
            {{-- <div class="mx-auto">{{$transactions->appends(request()->except('page'))->links()}} --}}
        </div>
    </div>
</div>

</div>
@endsection