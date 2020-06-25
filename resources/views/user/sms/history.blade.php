@extends('user.layout')
@section('title','SMS History')

@section('content')
<div id="row">
    <div id="col-12">
        @if(env('SMS_ALERT'))
        <div class="alert alert-danger rounded m-3">
            {!! env('SMS_ALERT') !!}
        </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-12 mx-auto card p-5">
        <h4 class="text-6 mb-4">SMS History</h4>
        <div class="card">
            <div class="card-header">

                <div class="">
                    <form class="form-inline" action="{{url()->current()}}">

                        <div class="input-transaction input-transaction-sm my-1 mr-1">
                            <div class="input-transaction-prepend">
                                <span class="input-transaction-text">From</span>
                            </div>
                            <input type="date" name="from" value="{{$from->format('Y-m-d')}}" class="form-control"
                                aria-label="Small">
                        </div>
                        <div class="input-transaction input-transaction-sm my-1 mr-1">
                            <div class="input-transaction-prepend">
                                <span class="input-transaction-text">To</span>
                            </div>
                            <input type="date" name="to" value="{{$to->format('Y-m-d')}}" class="form-control"
                                aria-label="Small">
                        </div>

                        <div class="input-transaction input-transaction-sm my-1 mr-1">
                            <div class="input-transaction-prepend">
                                <span class="input-transaction-text">Ref</span>
                            </div>
                            <input name="ref" value="{{$ref}}" class="form-control" aria-label="Small">
                        </div>

                        <div class="input-transaction input-transaction-sm my-1 mr-1">
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
                            <th>Amount</th>
                            <th>Sender</th>
                            <th>Message</th>
                            <th>Desc</th>
                            <th>Success Numbers</th>
                            <th>Failed Numbers</th>
                            <th>Created At</th>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{$transaction->id}}</td>
                                <td>{{$transaction->transaction->ref}}</td>
                                <td>{{$transaction->transaction->amount}}</td>
                                <td>{{$transaction->sender}}</td>
                                <td>{{$transaction->message}}</td>
                                <td>{{$transaction->transaction->desc}}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm text-white"
                                        onclick="showNumbers('{{$transaction->success_numbers}}')">View Numbers</a>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm text-white"
                                        onclick="showNumbers('{{$transaction->failed_numbers}}')">View Numbers</a>
                                </td>
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
@endsection