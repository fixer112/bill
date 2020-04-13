@extends('layout')
@section('title','Home')
@section('content')
@guest
<section class="container">
    <div class="row mt-4">
        <div class="col-md-12 col-lg-10">
            <div id="verticalTab">
                <div class="row no-gutters">
                    <div class="col-md-3 my-0 my-md-4">
                        <ul class="resp-tabs-list">
                            <li><span><i class="fas fa-phone"></i></span> Airtime</li>
                            <li><span><i class="fas fa-globe"></i></span>Data</li>
                        </ul>
                    </div>
                    <div class="col-md-9">
                        <div class="resp-tabs-container bg-light shadow-md rounded h-100 p-3">

                            <!-- Mobile Recharge -->
                            <div>
                                <h2 class="text-6 mb-4">Mobile Recharge </h2>
                                <form id="recharge-bill" method="post">
                                    {{-- <div class="mb-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input id="prepaid" name="rechargeBillpayment" class="custom-control-input"
                                                checked="" required type="radio">
                                            <label class="custom-control-label" for="prepaid">Prepaid</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input id="postpaid" name="rechargeBillpayment" class="custom-control-input"
                                                required type="radio">
                                            <label class="custom-control-label" for="postpaid">Postpaid</label>
                                        </div>
                                    </div> --}}
                                    <div class="form-group">
                                        <label for="mobileNumber">Mobile Number</label>
                                        <input type="text" class="form-control" data-bv-field="number" id="mobileNumber"
                                            required placeholder="Enter Mobile Number">
                                    </div>
                                    <div class="form-group">
                                        <label for="operator">Your Operator</label>
                                        <select class="custom-select" id="operator" required="">
                                            <option value="">Select Your Operator</option>
                                            <option>1st Operator</option>
                                            <option>2nd Operator</option>
                                            <option>3rd Operator</option>
                                            <option>4th Operator</option>
                                            <option>5th Operator</option>
                                            <option>6th Operator</option>
                                            <option>7th Operator</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Custom Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"> <span class="input-group-text">$</span>
                                            </div>
                                            {{-- <a href="#" data-target="#view-plans" data-toggle="modal"
                                                class="view-plans-link">View Plans</a> --}}
                                            <input class="form-control" id="amount" placeholder="Enter Amount" required
                                                type="text">
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-block" type="submit">Continue to
                                        Recharge</button>
                                </form>
                            </div>
                            <!-- Mobile Recharge end -->

                            <!-- Data Recharge -->
                            <div>
                                <h2 class="text-6 mb-4">Data Recharge </h2>
                                <form id="recharge-bill" method="post">

                                    <div class="form-group">
                                        <label for="mobileNumber">Mobile Number</label>
                                        <input type="text" class="form-control" data-bv-field="number" id="mobileNumber"
                                            required placeholder="Enter Mobile Number">
                                    </div>
                                    <div class="form-group">
                                        <label for="operator">Your Operator</label>
                                        <select class="custom-select" id="operator" required="">
                                            <option value="">Select Your Operator</option>
                                            <option>1st Operator</option>
                                            <option>2nd Operator</option>
                                            <option>3rd Operator</option>
                                            <option>4th Operator</option>
                                            <option>5th Operator</option>
                                            <option>6th Operator</option>
                                            <option>7th Operator</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="operator">Plan</label>
                                        <select class="custom-select" id="operator" required="">
                                            <option value="">Select Plan</option>
                                            <option>1st Operator</option>
                                            <option>2nd Operator</option>
                                            <option>3rd Operator</option>
                                            <option>4th Operator</option>
                                            <option>5th Operator</option>
                                            <option>6th Operator</option>
                                            <option>7th Operator</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Cost</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"> <span class="input-group-text">$</span>
                                            </div>

                                            <input class="form-control" id="amount" placeholder="Enter Amount" disabled
                                                type="text">
                                        </div>
                                    </div>

                                    <button class="btn btn-primary btn-block" type="submit">Continue to
                                        Recharge</button>
                                </form>
                            </div>
                            <!-- Data Recharge end -->

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner
        ============================================= -->
        <div class="col-lg-2 mt-4 mt-lg-0">
            <div class="row">
                <div class="col-6 col-lg-12 text-center"> <a href="#"><img src="images/slider/small-banner-7.jpg" alt=""
                            title="" class="img-fluid rounded shadow-md"></a> </div>
                <div class="col-6 col-lg-12 mt-lg-3 text-center"> <a href=""><img src="images/slider/small-banner-8.jpg"
                            alt="" title="" class="img-fluid rounded shadow-md"></a> </div>
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
                    <div class="item"><a href="#"><img class="img-fluid" src="images/slider/banner-1.jpg"
                                alt="banner 1" /></a>
                    </div>
                    <div class="item"><a href="#"><img class="img-fluid" src="images/slider/banner-2.jpg"
                                alt="banner 2" /></a>
                    </div>
                </div>
            </div><!-- Slideshow end -->
        </div>
    </div>
</section>
@endauth

<!-- Tabs -->
<div class="section pt-4 pb-3">
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
</div><!-- Tabs end -->

<!-- Refer & Earn -->
<div class="container">
    <section class="section bg-light shadow-md rounded px-5">
        <h2 class="text-9 font-weight-600 text-center">Refer & Earn</h2>
        <p class="lead text-center mb-5">Refer your friends and earn up to $20.</p>
        <div class="row">
            <div class="col-md-4">
                <div class="featured-box style-4">
                    <div class="featured-box-icon bg-light-4 text-primary rounded-circle"> <i
                            class="fas fa-bullhorn"></i> </div>
                    <h3>You Refer Friends</h3>
                    <p class="text-3">Share your referral link with friends. They get $10.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="featured-box style-4">
                    <div class="featured-box-icon bg-light-4 text-primary rounded-circle"> <i
                            class="fas fa-sign-in-alt"></i> </div>
                    <h3>Your Friends Register</h3>
                    <p class="text-3">Your friends Register with using your referral link.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="featured-box style-4">
                    <div class="featured-box-icon bg-light-4 text-primary rounded-circle"> <i
                            class="fas fa-dollar-sign"></i> </div>
                    <h3>Earn You</h3>
                    <p class="text-3">You get $20. You can us these credits to take recharge.</p>
                </div>
            </div>
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
                <p>Ridens mediocritatem ius an, eu nec magna imperdiet. Mediocrem qualisque in has. Enim
                    utroque perfecto id mei, ad eam tritani labores facilisis, ullum sensibus no cum.
                    Eius eleifend in quo.</p>
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