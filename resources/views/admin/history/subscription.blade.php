@extends('admin.layout')
@section('title','Subsription History')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col">
                        <i class="fa fa-money-bill f-30 text-c-green"></i>
                        <h6 class="m-t-50 m-b-0">Total Subscriptions</h6>
                    </div>
                    <div class="col text-right">
                        <h3 class="text-c-green f-w-300">
                            {{currencyFormat($totalSubscriptions->sum('amount'))}}</h3>
                        <span class="text-muted d-block">Total Amount </span>
                        <span
                            class="badge theme-bg text-white m-t-20">{{wholeNumberFormat($totalSubscriptions->count())}}</span>
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
                        <h6 class="m-t-50 m-b-0">Total Subscriptions</h6>
                    </div>
                    <div class="col text-right">
                        <h3 class="text-c-green f-w-300">
                            {{currencyFormat($totalSubscriptions->sum('bonus'))}}</h3>
                        <span class="text-muted d-block">Total Bonus </span>
                        <span
                            class="badge theme-bg text-white m-t-20">{{wholeNumberFormat($totalSubscriptions->count())}}</span>
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
                        <h6 class="m-t-50 m-b-0">Total Subscriptions</h6>
                    </div>
                    <div class="col text-right">
                        <h3 class="text-c-green f-w-300">
                            {{currencyFormat($s->sum('amount'))}}</h3>
                        <span class="text-muted d-block">Total Amount </span>
                        <span class="badge theme-bg text-white m-t-20">{{wholeNumberFormat($s->count())}}</span>
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
                        <h6 class="m-t-50 m-b-0">Total Subscriptions</h6>
                    </div>
                    <div class="col text-right">
                        <h3 class="text-c-green f-w-300">
                            {{currencyFormat($subscriptions->sum('bonus'))}}</h3>
                        <span class="text-muted d-block">Total Bonus </span>
                        <span
                            class="badge theme-bg text-white m-t-20">{{wholeNumberFormat($subscriptions->count())}}</span>
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
                            <th>Id</th>
                            <th>Ref</th>
                            <th>Username</th>
                            <th>Amount</th>
                            <th>Bonus</th>
                            <th>Package</th>
                            <th>Upgraded From</th>
                            <th>Created At</th>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $subscription)
                            <tr>
                                <td>{{$subscription->id}}</td>
                                <td>{{$subscription->transaction->ref}}</td>
                                <td class="font-weight-bold">
                                    <a href="/user/{{$subscription->transaction->user->id}}">
                                        {{$subscription->transaction->user->login}}</a>
                                </td>
                                <td>{{numberFormat($subscription->amount)}}</td>
                                <td>{{numberFormat($subscription->bonus)}}</td>
                                <td><a class="label rounded-pill text-white theme-bg">{{$subscription->name}}</a></td>
                                <td>
                                    @if($subscription->lastSub !='')
                                    <a class="label rounded-pill text-white theme-bg2">{{$subscription->lastSub}}
                                    </a>
                                    @endif
                                </td>
                                <td>{{$subscription->created_at}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mx-auto">{{$subscriptions->appends(request()->except('page'))->links()}}
            </div>
        </div>
    </div>
</div>

</div>
@endsection