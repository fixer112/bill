@extends('user.layout')
@section('title','Wallet History')
@section('js')
{{-- <script src="/assets/plugins/footable/js/footable.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        
        
            // [ Foo-table ]
            $('#foo-wallet').footable({
               // "paging": {
                 //   "enabled": true
               // },
                "sorting": {
                    "enabled": true
                }
            });
        });
</script> --}}
@endsection
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card theme-bg">
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
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col">
                        <i class="fa fa-money-bill f-30 text-c-green"></i>
                        <h6 class="m-t-50 m-b-0">Total Credit Transactions</h6>
                    </div>
                    <div class="col text-right">
                        <h3 class="text-c-green f-w-300">{{currencyFormat($totalCredit->sum('amount'))}}</h3>
                        <span class="text-muted d-block">Total Credit Amount </span>
                        <span
                            class="badge theme-bg text-white m-t-20">{{wholeNumberFormat($totalCredit->count())}}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-4">
        <div class="card rounded">
            <div class="card-block">
                <div class="row">
                    <div class="col">
                        <i class="fa fa-money-bill f-30 text-c-green"></i>
                        <h6 class="m-t-50 m-b-0">Total Debit Transactions</h6>
                    </div>
                    <div class="col text-right">
                        <h3 class="text-c-green f-w-300">{{currencyFormat($totalDebit->sum('amount'))}}</h3>
                        <span class="text-muted d-block">Total Debit Amount </span>
                        <span
                            class="badge theme-bg text-white m-t-20">{{wholeNumberFormat($totalDebit->count())}}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col">
                        <i class="fa fa-money-bill f-30 text-c-green"></i>
                        <h6 class="m-t-50 m-b-0">Credit Transactions</h6>
                    </div>
                    <div class="col text-right">
                        <h3 class="text-c-green f-w-300">{{currencyFormat($credit->sum('amount'))}}</h3>
                        <span class="text-muted d-block">Credit Amount </span>
                        <span class="badge theme-bg text-white m-t-20">{{wholeNumberFormat($credit->count())}}</span>
                    </div>
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
                        <h6 class="m-t-50 m-b-0">Debit Transactions</h6>
                    </div>
                    <div class="col text-right">
                        <h3 class="text-c-green f-w-300">{{currencyFormat($debit->sum('amount'))}}</h3>
                        <span class="text-muted d-block">Debit Amount </span>
                        <span class="badge theme-bg text-white m-t-20">{{wholeNumberFormat($debit->count())}}</span>
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
                                <span class="input-group-text">Type</span>
                            </div>
                            <select class="custom-select" name="type" aria-label="Small">
                                <option value="">All</option>
                                @foreach ($types as $t)
                                <option value="{{$t}}" {{$t == $type ? 'selected': ''}}>{{ucfirst($t)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group input-group-sm my-1 mr-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Reason</span>
                            </div>
                            <select class="custom-select" name="reason" aria-label="Small">
                                <option value="">All</option>
                                @foreach ($reasons as $r)
                                <option value="{{$r}}" {{$r == $reason ? 'selected': ''}}>{{ucfirst($r)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group input-group-sm my-1 mr-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Ref</span>
                            </div>
                            <input type="text" name="ref" placeholder="Search by reference" value="{{$ref}}"
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
                            <th>Amount</th>
                            <th>Balance</th>
                            @can('manageRoles',App\User::class)
                            <th>Profit</th>
                            @endcan
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
                                <td>{{$transaction->amount}}</td>
                                <td>{{$transaction->balance}}</td>
                                @can('manageRoles', App\User::class)
                                <td>{{numberFormat($transaction->profit)}}</td>
                                @endcan
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
            {{-- <div class="mx-auto">{{$transactions->appends(request()->except('page'))->links()}} --}}
        </div>
    </div>
</div>

</div>
@endsection