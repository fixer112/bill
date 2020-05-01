@extends('layout')
@section('title','Pricing')
@section('content')
<div class="container">
    <section class="section bg-light shadow-md rounded px-5 my-3">
        <h2 class="text-9 font-weight-600 text-center">Discounts</h2>
        <div class="table-responsive">
            <table class="table table-hover ">
                <thead>
                    <th>DISCOUNTS</th>
                    <th>GUEST</th>
                    <th>INDIVIDUAL</th>
                    @foreach (config("settings.subscriptions") as $key =>$item)
                    <th>{{strtoupper($key)}}</th>
                    @endforeach
                </thead>
                <tbody>
                    @php
                    $keys =['airtime','data'];
                    @endphp
                    @foreach (config("settings.mobile_networks") as $key =>$value )
                    @for ($i =0 ; $i <2 ; $i++) <tr>
                        <td>{{strtoupper($key)}} {{strtoupper($keys[$i])}}</td>
                        <td>0%</td>
                        <td>{{config("settings.individual.bills.{$keys[$i]}.$key")}}%</td>
                        @foreach ( config("settings.subscriptions") as $name => $item)
                        <td>{{$item['bills'][$keys[$i]][$key]}}%</td>
                        @endforeach
                        </tr>
                        @endfor

                        @endforeach
                        <tr>
                            <td>API TROTTLE LIMIT/MINUTE</td>
                            <td> 60 </td>
                            <td> 60 </td>
                            @foreach ( config("settings.subscriptions") as $name => $item)
                            <td>{{$item['rate_limit']}}</td>
                            @endforeach

                        </tr>
                        <tr>
                            <td>BALANCE AFTER SETUP</td>
                            <td> - </td>
                            <td> - </td>
                            @foreach ( config("settings.subscriptions") as $name => $item)
                            <td>{{currencyFormat(calPercentageAmount($item['amount'],$item['bonus']))}}</td>
                            @endforeach

                        </tr>
                        <tr>
                            <td>SETUP CAPITAL</td>
                            <td> - </td>
                            <td> - </td>
                            @foreach ( config("settings.subscriptions") as $name => $item)
                            <td>{{currencyFormat($item['amount'])}}</td>
                            @endforeach

                        </tr>

                        <tr>
                            <td>PORTAL OWNER</td>
                            <td> - </td>
                            <td> - </td>
                            @foreach ( config("settings.subscriptions") as $name => $item)
                            <td>{{$item['portal'] ? 'YES' : 'NO'}}</td>
                            @endforeach

                        </tr>

                </tbody>
            </table>
        </div>
        <div class="">
            <a href="/register">
                <button class="btn btn-block btn-primary text-uppercase text-white">Sign
                    Up As Reseller</button>
            </a>
        </div>
    </section>

    <section class="section bg-light shadow-md rounded px-5 my-3">
        <h2 class="text-9 font-weight-600 text-center mb-4">Data Pricing</h2>
        @foreach ( config("settings.mobile_networks") as $key=> $item)
        <div class="card mb-3 p-2">
            <a data-toggle="collapse" href="#{{preg_replace('/[0-9]+/', '',$key)}}" role="button" aria-expanded="false"
                aria-controls="{{preg_replace('/[0-9]+/', '',$key)}}">
                <h4 class=" font-weight-600 text-center">{{strtoupper($key)}}
                </h4>
            </a>
            <div class="collapse" id="{{preg_replace('/[0-9]+/', '',$key)}}">
                <div class="table-responsive mb-2">
                    <table class="table table-hover ">
                        <thead>
                            <th>Plan</th>
                            <th>Price</th>
                            <th>Validity</th>

                        </thead>
                        <tbody>
                            @foreach ( config("settings.bills.data.{$key}") as $plan => $item)
                            <tr>
                                <td>{{getLastString($item['id'])}}</td>
                                <td>{{currencyFormat($item['price'])}}</td>
                                <td>{{$item['validity']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
        <div class="">
            <a href="/register">
                <button class="btn btn-block btn-primary text-uppercase text-white">Sign
                    Up As Reseller</button>
            </a>
        </div>
    </section>
</div>
@endsection