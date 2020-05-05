@extends('layout')
@section('title','Home')
@section('head')
<script src="https://js.paystack.co/v1/inline.js"></script>
<style>
    .side {
        height: 273px;
        width: 100%;
    }
</style>
@endsection
@section('content')
{{-- @json(config("settings.bills.data")) --}}
@guest
<section class="container mb-3">
    <div class="row mt-4">
        <div class="col-md-12 col-lg-9">
            <div id="verticalTab">
                <div class="row no-gutters">
                    <div class="col-md-3 my-0 my-md-4">
                        <ul class="resp-tabs-list">
                            <li class="resp-tab-item resp-tab-active"><span><i class="fas fa-phone"></i></span> Airtime
                            </li>
                            <li class="resp-tab-item"><span><i class="fas fa-globe"></i></span>Data</li>

                            <li class="resp-tab-item"><span><i class="fas fa-tv"></i></span>Cable Tv</li>
                        </ul>
                    </div>
                    <div class="col-md-9">

                        <div class="resp-tabs-container bg-light shadow-md rounded h-100 p-3">



                            <h4 class="ml-3">GUEST</h4>


                            <!-- Mobile Recharge -->

                            <form id="airtime-home">
                                <div class="resp-tab-content resp-tab-content-active" style="display:block"
                                    aria-labelledby="tab_item-0">

                                    <x-airtime :dat="airtimeDiscount()" guest="1" />
                                </div>

                            </form>

                            {{--  @json(session('balance'))  --}}
                            <script>
                                document.getElementById('airtime-home').addEventListener("submit",function(event){

                                event.preventDefault();
                                var data = {
                                            reason: 'airtime',
                                            amount:airtime.discountAmount,
                                            network:airtime.network,
                                            network_code:airtime.network_code,
                                            number:airtime.number,

                                        };
                                //console.log(data['reason']);
                                processPayment(data,@json(session('balance'))[0],"{{env('ENABLE_BILL_PAYMENT')}}","{{env('ERROR_MESSAGE')}}",'{{env("PAYSTACK_KEY")}}');
                                });
                                
                                
                            </script>


                            <!-- Mobile Recharge end -->

                            <!-- Data Recharge -->
                            <form id="data-home">

                                <div class="resp-tab-content" aria-labelledby="tab_item-1">

                                    <x-data :dat="dataDiscount()" guest="true" />
                                </div>
                            </form>
                            <script>
                                document.getElementById('data-home').addEventListener("submit",function(event){

                                event.preventDefault();
                                var d = {
                                            reason: 'data',
                                            amount:data.discountAmount,
                                            network:data.network,
                                            network_code:data.network_code,
                                            number:data.number,
                                            details:data.details,

                                        };
                                //console.log(data);
                                processPayment(d,@json(session('balance'))[0],"{{env('ENABLE_BILL_PAYMENT')}}","{{env('ERROR_MESSAGE')}}",'{{env("PAYSTACK_KEY")}}');
                                });
                            </script>
                            <!-- Data Recharge end -->


                            <!-- Cable -->

                            <form id="cable-home">

                                <div class="resp-tab-content" aria-labelledby="tab_item-2">

                                    <x-cable :dat="cableDiscount()" guest="true" />
                                </div>
                            </form>
                            <script>
                                document.getElementById('cable-home').addEventListener("submit",function(event){
                            
                                    event.preventDefault();
                                    var d = {
                                                reason: 'cable',
                                                amount:cable.discountAmount,
                                                details:cable.details,

                                            };
                                    $.notify({
                                    // options
                                    message: "Cable Coming Soon to guest user, please register to use for registered user and enjoy amazing discounts",
                                    }, {
                                    // settings
                                    type: 'danger'
                                    });
                                    //console.log(data);
                                    //processPayment(d,@json(session('balance'))[0],"{{env('ENABLE_BILL_PAYMENT')}}","{{env('ERROR_MESSAGE')}}",'{{env("PAYSTACK_KEY")}}');
                                    });
                            </script>

                            <!-- Cable end -->

                            <p class="text-danger ml-3 text-center">

                                For any complain or refund request please click <a href="/contact">here</a>
                            </p>

                            <h5 class="mb-4 text-center"><a href="/register"> REGISTER NOW TO ENJOY AMAZING
                                    DISCOUNTS</a></h5>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner
        ============================================= -->
        <div class="col-lg-3 mt-4 mt-lg-0">
            <div class="row">
                @for ($i =0 ; $i <2 ; $i++) <div class="col-6 col-lg-12 mt-lg-3 text-center"> <a href=""><img
                            src="/{{$sides->random()}}" alt="" title="" class="img-fluid rounded shadow-md side"></a>
            </div>
            @endfor

        </div>
    </div>
    <!-- Banner end -->

    </div>
</section>
@endguest

@auth
<section class="container">
    <div class="bg-light shadow-md rounded p-2 m-3">
        <div class="row">
            <!-- Slideshow
          ============================================= -->
            <div class="col-lg-12">
                <div class="owl-carousel owl-theme slideshow single-slider">
                    @foreach ($sliders as $slider)
                    <div class="item"><a href="#"><img class="img-fluid" src="/{{$slider}}" alt="banner 1" /></a>
                    </div>
                    @endforeach
                </div>
            </div><!-- Slideshow end -->
        </div>
    </div>
</section>
@endauth

<!-- Tabs -->
{{-- <div class="section pt-4 pb-3">
    <div class="container">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item"> <a class="nav-link active" id="mobile-recharge-tab" data-toggle="tab"
                    href="#mobile-recharge" role="tab" aria-controls="mobile-recharge" aria-selected="true">Mobile
                    Recharge</a> </li>
            <li class="nav-item"> <a class="nav-link" id="billpayment-tab" data-toggle="tab" href="#billpayment"
                    role="tab" aria-controls="billpayment" aria-selected="false">Bill Payment</a> </li>
            <li class="nav-item"> <a class="nav-link" id="why-quickai-tab" data-toggle="tab" href="#why-quickai"
                    role="tab" aria-controls="why-quickai" aria-selected="false">Why
                    Quickai</a> </li>
        </ul>
        <div class="tab-content my-3" id="myTabContent">
            <div class="tab-pane fade show active" id="mobile-recharge" role="tabpanel"
                aria-labelledby="mobile-recharge-tab">
                <p>Instant Online mobile recharge Iisque persius interesset his et, in quot quidam
                    persequeris vim, ad mea essent possim iriure. Mutat tacimates id sit. Ridens
                    mediocritatem ius an, eu nec magna imperdiet. Mediocrem qualisque in has. Enim
                    utroque perfecto id mei, ad eam tritani labores facilisis, ullum sensibus no cum.
                    Eius eleifend in quo. At mei alia iriure propriae.</p>
                <p>Partiendo voluptatibus ex cum, sed erat fuisset ne, cum ex meis volumus mentitum.
                    Alienum pertinacia maiestatis ne eum, verear persequeris et vim. Mea cu dicit
                    voluptua efficiantur, nullam labitur veritus sit cu. Eum denique omittantur te, in
                    justo epicurei his, eu mei aeque populo. Cu pro facer sententiae, ne brute graece
                    scripta duo. No placerat quaerendum nec, pri alia ceteros adipiscing ut. Quo in
                    nobis nostrum intellegebat. Ius hinc decore erroribus eu, in case prima exerci pri.
                    Id eum prima adipisci. Ius cu minim theophrastus, legendos pertinacia an nam. <a href="#">Read
                        Terms</a></p>
            </div>
            <div class="tab-pane fade" id="billpayment" role="tabpanel" aria-labelledby="billpayment-tab">
                <p>Partiendo voluptatibus ex cum, sed erat fuisset ne, cum ex meis volumus mentitum.
                    Alienum pertinacia maiestatis ne eum, verear persequeris et vim. Mea cu dicit
                    voluptua efficiantur, nullam labitur veritus sit cu. Eum denique omittantur te, in
                    justo epicurei his, eu mei aeque populo. Cu pro facer sententiae, ne brute graece
                    scripta duo. No placerat quaerendum nec, pri alia ceteros adipiscing ut. Quo in
                    nobis nostrum intellegebat. Ius hinc decore erroribus eu, in case prima exerci pri.
                    Id eum prima adipisci. Ius cu minim theophrastus, legendos pertinacia an nam.</p>
                <p>Instant Online mobile recharge Iisque persius interesset his et, in quot quidam
                    persequeris vim, ad mea essent possim iriure. Mutat tacimates id sit. Ridens
                    mediocritatem ius an, eu nec magna imperdiet. Mediocrem qualisque in has. Enim
                    utroque perfecto id mei, ad eam tritani labores facilisis, ullum sensibus no cum.
                    Eius eleifend in quo. At mei alia iriure propriae.</p>
            </div>
            <div class="tab-pane fade" id="why-quickai" role="tabpanel" aria-labelledby="why-quickai-tab">
                <p>Cu pro facer sententiae, ne brute graece scripta duo. No placerat quaerendum nec, pri
                    alia ceteros adipiscing ut. Quo in nobis nostrum intellegebat. Ius hinc decore
                    erroribus eu, in case prima exerci pri. Id eum prima adipisci. Ius cu minim
                    theophrastus, legendos pertinacia an nam.</p>
                <p>Partiendo voluptatibus ex cum, sed erat fuisset ne, cum ex meis volumus mentitum.
                    Alienum pertinacia maiestatis ne eum, verear persequeris et vim. Mea cu dicit
                    voluptua efficiantur, nullam labitur veritus sit cu. Eum denique omittantur te, in
                    justo epicurei his, eu mei aeque populo.</p>
                <p>Instant Online mobile recharge Iisque persius interesset his et, in quot quidam
                    persequeris vim, ad mea essent possim iriure. Mutat tacimates id sit. Ridens
                    mediocritatem ius an, eu nec magna imperdiet. Mediocrem qualisque in has. Enim
                    utroque perfecto id mei, ad eam tritani labores facilisis, ullum sensibus no cum.
                    Eius eleifend in quo. At mei alia iriure propriae.</p>
            </div>
        </div>
    </div>
</div> --}}
<!-- Tabs end -->
<!--<div class="container">
    <section class="section pricing bg-light shadow-md rounded px-5 mb-3">
        <div class="container">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <div class="card mb-5 mb-lg-0">
                        <div class="card-body">
                            <h5 class="card-title text-muted text-uppercase text-center">Guest</h5>
                            <h6 class="card-price text-center">{{currencyFormat(0)}}<span class="period">/Forever</span>
                            </h6>

                            <hr>
                            <ul class="fa-ul">
                                <li><span class="fa-li"><i class="fas fa-check"></i></span>0 Api
                                    Throttle limit / Minutes</li>
                                <li><span class="fa-li"><i class="fas fa-check"></i></span>{{currencyFormat(0)}}
                                    Balance After Setup
                                </li>
                                <li><span class="fa-li"><i class="fas fa-check"></i></span>No Payments Discount</li>
                                {{-- <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Unlimited
                                                                    Private
                                                                    Projects</li> --}}

                            </ul>
                            <a href="/pricing"><button class="btn btn-block btn-primary text-uppercase text-white">Check
                                    Out
                                    Pricing</button></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card mb-5 mb-lg-0">
                        <div class="card-body">
                            <h5 class="card-title text-muted text-uppercase text-center">Individual</h5>
                            <h6 class="card-price text-center">{{currencyFormat(0)}}<span class="period">/Forever</span>
                            </h6>
                            <hr>
                            <ul class="fa-ul">
                                <li><span class="fa-li"><i class="fas fa-check"></i></span>60 Api
                                    Throttle limit / Minutes</li>
                                <li><span class="fa-li"><i class="fas fa-check"></i></span>{{currencyFormat(0)}}
                                    Balance After Setup
                                </li>
                                <li><span class="fa-li"><i class="fas fa-check"></i></span>Bill Payments Discount</li>
                                {{-- <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Unlimited
                                                    Private
                                                    Projects</li> --}}

                            </ul>
                            <a href="/pricing"><button class="btn btn-block btn-primary text-uppercase text-white">Check
                                    Out
                                    Pricing</button></a>
                        </div>
                    </div>
                </div>
                @foreach (config("settings.subscriptions") as$key=> $item)
                <div class="col-md-4 mb-3">
                    <div class="card mb-5 mb-lg-0">
                        <div class="card-body">
                            <h5 class="card-title text-muted text-uppercase text-center">{{$key}}</h5>
                            <h6 class="card-price text-center">{{currencyFormat($item['amount'])}}<span
                                    class="period">/Forever</span></h6>
                            <hr>
                            <ul class="fa-ul">
                                <li><span class="fa-li"><i class="fas fa-check"></i></span>{{$item['rate_limit']}} Api
                                    Throttle limit / Minutes</li>
                                <li><span class="fa-li"><i
                                            class="fas fa-check"></i></span>{{currencyFormat(calPercentageAmount($item['amount'],$item['bonus']))}}
                                    Balance After Setup
                                </li>
                                <li><span class="fa-li"><i class="fas fa-check"></i></span>Bill Payments Discount</li>
                                {{-- <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Unlimited
                                    Private
                                    Projects</li> --}}

                            </ul>
                            <a href="/pricing"><button class="btn btn-block btn-primary text-uppercase text-white">Check
                                    Out
                                    Pricing</button></a>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
</div>-->
<!-- Refer & Earn -->
<div class="container">
    <section class="section bg-light shadow-md rounded px-5">
        <h2 class="text-9 font-weight-600 text-center">Refer & Earn</h2>
        <p class="lead text-center mb-5">Refer your friends and earn as much as possible.</p>
        <div class="row">
            <div class="col-md-6">
                <div class="featured-box style-4">
                    <div class="featured-box-icon bg-light-4 text-primary rounded-circle"> <i
                            class="fas fa-bullhorn"></i> </div>
                    <h3>You Refer Friends</h3>
                    <p class="text-3">Share your referral link with friends.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="featured-box style-4">
                    <div class="featured-box-icon bg-light-4 text-primary rounded-circle"> <i
                            class="fas fa-sign-in-alt"></i> </div>
                    <h3>Your Friends Register</h3>
                    <p class="text-3">Your friends Register with using your referral link and you get
                        {{currencySymbol()}}100, on their first payment</p>
                </div>
            </div>
            <!-- <div class="col-md-6">
                <div class="featured-box style-4">
                    <div class="featured-box-icon bg-light-4 text-primary text-center rounded-circle"> <i
                            class="{{-- fas fa-dollar-sign --}} font-weight-bold mx-auto"
                            style="font-size: 2.9rem;">{{currencySymbol()}}</i>
                    </div>
                    <h3>You Earn</h3>
                    <p class="text-3">You get {{currencySymbol()}}50. You can use this credits to make bill payments.
                    </p>
                </div>
            </div> -->
        </div>
        <div class="text-center pt-4"> <a href="/register" class="btn btn-primary">Sign Up Now</a>
        </div>
    </section>
</div><!-- Refer & Earn end -->

<!-- Mobile App -->
<section class="section pb-0">
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-lg-6 text-center"> <img class="img-fluid" alt="" src="images/app-mobile.png">
            </div>
            <div class="col-md-7 col-lg-6">
                <h2 class="text-9 font-weight-600 my-4">Our {{ucfirst(strtolower(env('APP_NAME')))}}
                    Mobile App<br class="d-none d-lg-inline-block">
                    Coming Soon</h2>
                <p class="lead">Download our app for the fastest, most convenient way to send Recharge.
                </p>
                <p>{{env('APP_DESCRIPTION')}}</p>
                <ul>
                    <li>Recharge</li>
                    <li>Bill Payment</li>
                    <li>and much more.....</li>
                </ul>
                {{-- <div class="d-flex flex-wrap pt-2"> <a class="mr-4" href=""><img alt=""
                                            src="images/app-store.png"></a><a href=""><img alt=""
                                            src="images/google-play-store.png"></a> </div>
                            </div> --}}
            </div>
        </div>
</section><!-- Mobile App end -->
@endsection