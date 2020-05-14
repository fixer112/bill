@extends('user.layout')
@section('title','Dashboard')
@section('head')
<link rel="stylesheet" href="/assets/plugins/footable/css/footable.bootstrap.min.css">
<link rel="stylesheet" href="/assets/plugins/footable/css/footable.standalone.min.css">
@endsection
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

            $('#foo-referral').footable({
            // "paging": {
            // "enabled": true
            // },
            "sorting": {
            "enabled": true
            }
            });
        });
</script> --}}
@endsection
@section('content')
{{-- <div class="col-8 mx-auto"> --}}

<div class="row">
    <div class="col-md-4">
        <div class="card theme-bg ">
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
        <div class="card theme-bg2">
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
        <div class="card ">

            <div class="profile-userpic">
                <img src="{{request()->user->profilePic()}}" class="img-responsive" alt="">
            </div>


            <div class="profile-usertitle">
                <div class="profile-usertitle-name">
                    {{request()->user->full_name}} ({{request()->user->status()}})

                </div>
                <div class="profile-usertitle-job">
                    {{request()->user->type()}} Account
                    @if (request()->user->is_reseller)
                    <div>
                        <span
                            class="badge badge-primary badge-pill">{{request()->user->lastSub() ? request()->user->lastSub()->name : 'Awaiting Subscription'}}</span>
                    </div>
                    @endif
                </div>
            </div>



            <div class="info theme-bg p-2 text-white">
                <span class="float-left ml-1 font-weight-bold">REFERRAL POINTS</span>
                <span
                    class="float-right mr-1 font-italic font-weight-bold">{{wholeNumberFormat(request()->user->points)}}</span>
            </div>

            <div class="info theme-bg p-2 text-white">
                <span class="float-left ml-1 font-weight-bold">DIRECT REFERRAL</span>
                <span
                    class="float-right mr-1 font-italic font-weight-bold">{{wholeNumberFormat($directReferral->count())}}</span>
            </div>

            <div class="info theme-bg p-2 text-white">
                <span class="float-left ml-1 font-weight-bold">INDIRECT REFERRAL</span>
                <span
                    class="float-right mr-1 font-italic font-weight-bold">{{wholeNumberFormat($indirectReferral->count())}}</span>
            </div>

            <div class="info theme-bg p-2 text-white">
                <span class="float-left ml-1 font-weight-bold">LEVEL</span>
                <span
                    class="float-right mr-1 font-italic font-weight-bold">{{ucfirst(request()->user->getReferralLevel())}}</span>
            </div>





            <div class="m-2 small referral-link theme-bg p-2 rounded text-white">
                <span> Referral Link : <a
                        href="{{request()->user->getReferralLink()}}">{{request()->user->getReferralLink()}}</a></span>
            </div>

            @can('delete',request()->user)
            <div class="d-flex justify-content-center">
                <a href="{{request()->user->routePath()}}/status/update" <button
                    class="btn {{request()->user->is_active? 'btn-danger':'btn-success'}}">{{request()->user->is_active? 'Suspend User':'Activate User'}}</button></a>
            </div>
            @endcan


            {{-- <div class="profile-userbuttons">
                <button type="button" class="btn btn-success btn-sm">Follow</button>
                <button type="button" class="btn btn-danger btn-sm">Message</button>
            </div> --}}




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
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Desc</th>
                                <th>Plathform</th>
                                <th>Created At</th>
                            </thead>
                            <tbody>
                                @foreach (request()->user->transactions->take(10) as $transaction)
                                <tr>
                                    <td>{{$transaction->ref}}</td>
                                    <td>{{$transaction->amount}}</td>
                                    <td>{{$transaction->balance}}</td>
                                    <td><a
                                            class="label rounded-pill text-white {{$transaction->type =='credit' ?'theme-bg':'theme-bg2'}}">{{$transaction->type}}</a>
                                    </td>
                                    <td>{{$transaction->reason}}</td>
                                    <td>{{$transaction->desc}}</td>
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
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Desc</th>
                                <th>User</th>
                                <th>Level</th>
                                <th>Created At</th>
                            </thead>
                            <tbody>
                                @foreach (request()->user->referrals->take(10) as $transaction)
                                <tr>
                                    <td>{{$transaction->id}}</td>
                                    <td>{{$transaction->amount}}</td>
                                    <td>{{$transaction->balance}}</td>
                                    <td>{{$transaction->desc}}</td>
                                    <td>{{$transaction->refered->login}}</td>
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


{{-- </div> --}}
@endsection