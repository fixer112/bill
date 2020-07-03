<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
        <link href="images/favicon.png" rel="icon" />

        <title>@yield('title') | {{env('APP_NAME')}} </title>
        <meta name="description" content="@yield('desc',env('APP_DESCRIPTION'))">
        <meta name="keywords" content="{{env('APP_KEYWORD')}}">
        <meta name="author" content="{{env('APP_NAME')}}" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta property="og:title" content="@yield('title') | {{env('APP_NAME')}}" />
        <meta property="og:description" content="@yield('desc',env('APP_DESCRIPTION'))" />
        <meta property="og:url" content="{{url()->current()}}" />
        <meta property="og:site_name" content="{{env('APP_NAME')}}" />
        <meta property="og:image" content="{{url('/images/logo2.png')}}" />
        <meta name="twitter:card" content="@yield('desc',env('APP_DESCRIPTION'))" />
        <meta name="twitter:url" content="{{url()->current()}}" />
        <meta name="twitter:title" content="@yield('title') | {{env('APP_NAME')}}" />
        <meta name="twitter:description" content="@yield('desc',env('APP_DESCRIPTION'))" />
        <meta name="twitter:image:src" content="{{url('/images/logo2.png')}}" />
        <meta name="twitter:site" content="@moniwallet" />

        <!-- Web Fonts
============================================= -->
        <link rel='stylesheet'
            href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>

        <!-- Stylesheet
============================================= -->
        <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/fonts/fontawesome/css/fontawesome-all.min.css">
        <link rel="stylesheet" type="text/css" href="/vendor/owl.carousel/assets/owl.carousel.min.css" />
        <link rel="stylesheet" type="text/css" href="/vendor/owl.carousel/assets/owl.theme.default.min.css" />
        <link rel="stylesheet" type="text/css" href="/css/stylesheet.css" />
        <link rel="stylesheet" type="text/css" href="/css/stylesheet.css" />
        <link rel="stylesheet" type="text/css" href="/css/custom.css" />
        <script src="/vendor/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="/js/floating-wpp.min.js"></script>
        <link rel="stylesheet" href="/css/floating-wpp.min.css">
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="/js/script.js"></script>

        <!--// https://firebase.google.com/docs/web/setup#available-libraries -->
        <script src="https://www.gstatic.com/firebasejs/7.15.1/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.15.1/firebase-analytics.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.15.1/firebase-messaging.js"></script>



        <style>
            .logo {
                margin-top: 10px;
            }
        </style>


        @yield('head')

    </head>

    <body>

        <!-- Preloader -->
        <div id="preloader">
            <div data-loader="dual-ring"></div>
        </div><!-- Preloader End -->

        <!-- Document Wrapper   
============================================= -->
        <div id="main-wrapper">

            <!-- Header
  ============================================= -->
            <header id="header">
                <div class="container">
                    <div class="header-row">
                        <div class="header-column justify-content-start">

                            <!-- Logo
          ============================================= -->
                            <div class="logo">
                                <a href="/" title="{{env('APP_DESCRIPTION')}}"><img src="images/logo.png"
                                        alt="{{env('APP_NAME')}}" {{-- width="127" height="29" --}} /></a>
                            </div><!-- Logo end -->

                        </div>

                        <div class="header-column justify-content-end">

                            <!-- Primary Navigation -->
                            <nav class="primary-menu navbar navbar-expand-lg">
                                <div id="header-nav" class="collapse navbar-collapse">
                                    <ul class="navbar-nav">
                                        <li class=""> <a href="/">Home</a></li>
                                        <li class=""> <a href="/pricing">Pricing</a></li>
                                        <li class=""> <a
                                                href="https://documenter.getpostman.com/view/4721383/Szf9UmbT">API</a>
                                        </li>

                                        {{-- <li class="dropdown"> <a class="dropdown-toggle" href="#">Features</a>
                                            <ul class="dropdown-menu">
                                                <li class="dropdown"><a class="dropdown-item dropdown-toggle"
                                                        href="#">Headers</a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="index.html">Light Version
                                                                (Default)</a></li>
                                                        <li><a class="dropdown-item" href="index-4.html">Dark
                                                                Version</a></li>
                                                        <li><a class="dropdown-item" href="index-5.html">Primary
                                                                Version</a></li>
                                                        <li><a class="dropdown-item" href="index-8.html">Transparent</a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                href="page-header-custom-background-with-transparent-header.html">Transparent
                                                                with border</a></li>
                                                    </ul>
                                                </li>
                                                <li class="dropdown"><a class="dropdown-item dropdown-toggle"
                                                        href="#">Navigation DropDown</a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="index.html">Light Version
                                                                (Default)</a></li>
                                                        <li><a class="dropdown-item" href="index-3.html">Dark
                                                                Version</a></li>
                                                        <li><a class="dropdown-item" href="index-6.html">Primary
                                                                Version</a></li>
                                                    </ul>
                                                </li>
                                                <li class="dropdown"><a class="dropdown-item dropdown-toggle"
                                                        href="#">Page Headers</a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item"
                                                                href="page-header-left-alignment.html">Left
                                                                Alignment</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="page-header-center-alignment.html">Center
                                                                Alignment</a></li>
                                                        <li><a class="dropdown-item" href="page-header-light.html">Light
                                                                Version</a></li>
                                                        <li><a class="dropdown-item" href="page-header-dark.html">Dark
                                                                Version</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="page-header-primary.html">Primary Version</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="page-header-custom-background.html">Custom
                                                                Background</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="page-header-custom-background-with-transparent-header.html">Custom
                                                                Background 2</a></li>
                                                    </ul>
                                                </li>
                                                <li><a class="dropdown-item" href="layout-boxed.html">Layout Boxed</a>
                                                </li>
                                            </ul>
                                        </li> --}}
                                        @if(Auth::guest())
                                        <li class="login-signup ml-lg-2"><a class="pl-lg-4 pr-0" {{-- data-toggle="modal"
                                                data-target="#login-signup" --}} href="/login" title="Login">Login </a>
                                        </li>
                                        <li class="login-signup ml-lg-2"><a class="pl-lg-4 pr-0" href="/register"
                                                title="register">Register </a>
                                        </li>
                                        @else
                                        <li class="login-signup ml-lg-2"><a class="pl-lg-4 pr-0"
                                                href="{{Auth::user()->routePath()}}" title="Dashboard">Dashbord{{-- <span
                                                    class="d-none d-lg-inline-block"><i
                                                        class="fas fa-user"></i></span> --}}</a></li>
                                        <li class="login-signup ml-lg-2"><a class="pl-lg-4 pr-0" href="/logout"
                                                title="Logout">Logout </a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </nav><!-- Primary Navigation end -->

                        </div>

                        <!-- Collapse Button
        ============================================= -->
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#header-nav">
                            <span></span> <span></span> <span></span> </button>
                    </div>
                </div>
            </header><!-- Header end -->

            <!-- Content -->
            <div id="content">
                <section class="container">
                    {{-- <div id="row">
                        <div id="col-12">

                            @if(session('success'))
                            <div class="alert alert-success rounded mt-3">
                                {{session('success')}}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger rounded m-3">
                {{session('error')}}
            </div>
        </div>
        @endif
        </div> --}}
        </section>
        @yield('content')

        </div><!-- Content end -->

        <!-- Footer -->
        <footer id="footer">
            <section class="section bg-light shadow-md pt-4 pb-3">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="featured-box text-center">
                                <div class="featured-box-icon"> <i class="fas fa-lock"></i> </div>
                                <h4>100% Secure Payments</h4>
                                <p>Moving your card details to a much more secured place.</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="featured-box text-center">
                                <div class="featured-box-icon"> <i class="fas fa-thumbs-up"></i> </div>
                                <h4>Trust pay</h4>
                                <p>100% Payment Protection. Easy Return Policy.</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="featured-box text-center">
                                <div class="featured-box-icon"> <i class="fas fa-bullhorn"></i> </div>
                                <h4>Refer & Earn</h4>
                                <p>Invite a friend to sign up and earn as much as possible.</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="featured-box text-center">
                                <div class="featured-box-icon"> <i class="far fa-life-ring"></i> </div>
                                <h4>24X7 Support</h4>
                                <p>We re here to help. Have a query and need help ? <a href="/contact">Click
                                        here</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <p>Payment</p>
                        <ul class="payments-types">
                            <li><a href="#" target="_blank"> <img data-toggle="tooltip" src="images/payment/visa.png"
                                        alt="visa" title="Visa"></a></li>

                            <li><a href="#" target="_blank"> <img data-toggle="tooltip"
                                        src="images/payment/paystack.png" alt="paystack" title="Paystack" width="90"
                                        height="34"></a></li>

                            <li><a href="#" target="_blank"> <img data-toggle="tooltip"
                                        src="images/payment/mastercard.png" alt="discover" title="Discover"></a>
                            </li>
                        </ul>
                    </div>
                    {{--  <div class="col-md-4 mb-3 mb-md-0">
                            <p>Subscribe</p>
                            <div class="input-group newsletter">
                                <input class="form-control" placeholder="Your Email Address" name="newsletterEmail"
                                    id="newsletterEmail" type="text">
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">Subscribe</button>
                                </span> </div>
                        </div>  --}}
                    <div class="col-md-4 offset-md-4 d-flex align-items-md-end flex-column">
                        <p>Keep in touch</p>
                        <ul class="social-icons">
                            <li class="social-icons-facebook"><a data-toggle="tooltip"
                                    href="{{config("settings.social.facebook")}}" target="_blank" title="Facebook"><i
                                        class="fab fa-facebook-f"></i></a></li>
                            <li class="social-icons-twitter"><a data-toggle="tooltip"
                                    href="{{config("settings.social.twitter")}}" target="_blank" title="Twitter"><i
                                        class="fab fa-twitter"></i></a></li>
                            <li class="social-icons-instagram"><a data-toggle="tooltip"
                                    href="{{config("settings.social.instagram")}}" target="_blank" title="Instagram"><i
                                        class="fab fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="footer-copyright">
                    <ul class="nav justify-content-center">
                        <li class="nav-item"> <a class="nav-link active" href="/about">About Us</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="/contact">Contact Us</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="/terms">Terms of Service</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="/privacy">Privacy Policy</a> </li>
                        {{-- <li class="nav-item"> <a class="nav-link" href="/support">Support</a> </li> --}}
                        {{-- <li class="nav-item"> <a class="nav-link" href="/faq">Faq</a> </li> --}}
                    </ul>
                    <p class="copyright-text">Copyright © {{date('Y')}} <a href="/">{{env('APP_NAME')}}</a>. All
                        Rights
                        Reserved | Developed by <a href="https://altechtic.com">Altechtic Solutions</a></p>

                </div>
            </div>
            <div class="floating-wpp"></div>
        </footer><!-- Footer end -->

        </div><!-- Document Wrapper end -->

        <!-- Back to Top
============================================= -->
        <a id="back-to-top" data-toggle="tooltip" title="Back to Top" href="javascript:void(0)"><i
                class="fa fa-chevron-up"></i></a>


        <!-- Script -->


        <script src="/js/notify.js"></script>
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
        <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="/vendor/owl.carousel/owl.carousel.min.js"></script>
        <script src="/vendor/easy-responsive-tabs/easy-responsive-tabs.js"></script>
        <script src="/js/theme.js"></script>
        @yield('js')



        <script>
            var alerted = localStorage.getItem('alerted');
            var timeout =localStorage.getItem('time');
            console.log((new Date().getTime() - timeout)/1000/60);
            @if (env('GENERAL_ALERT'))
            if(!alerted || (timeout != '' && (new Date().getTime() - timeout)/1000/60 > 60 )){
                
                swal("",`{{env("GENERAL_ALERT")}}`,{
                    
                });
                localStorage.setItem('alerted',true);
                //var timeout = new Date().getTime() + 60*60*1000;
                localStorage.setItem('time',Date.now());
            }
            @endif
            //console.log(localStorage.getItem('time') - Date.now());

            @if (session('success'))
            
            swal("",`{{session('success')}}`,"success",{
            buttons: true,
            //dangerMode: true,
            });
            @endif

            
            @if (session('error'))
            
            swal("",`{{session('error')}}`,"error",{
            buttons: true,
            dangerMode: true,
            });
            @endif
            $(document).ready(function () {
                wpChat('left');
        $('#verticalTab').easyResponsiveTabs({
        type: 'vertical', //Types: default, vertical, accordion
        });
        });

        var url = window.location;
        var link =url.origin + url.pathname;
        
        var element = $('.navbar-nav li a').filter(function() {
        //console.log(this);
        return this.href == link; //|| url.href.indexOf(this.href) == 0;
        });
        
        element.parent().addClass('active');

            $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        });

        </script>
        @if( env('APP_ENV') =='production')
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/5eb1911781d25c0e58490940/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
        </script>
        <!--End of Tawk.to Script-->
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-165374331-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
                  function gtag(){dataLayer.push(arguments);}
                  gtag('js', new Date());
                
                  gtag('config', 'UA-165374331-1');
        </script>
        <script type="module" src="/firebase.js"></script>
        @endif



        <script>
            // Your web app's Firebase configuration
            
        </script>

    </body>

</html>