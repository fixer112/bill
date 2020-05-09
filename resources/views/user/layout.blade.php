<!DOCTYPE html>
<html lang="en">

    <head>
        <title>@yield('title') | {{env('APP_NAME')}}</title>
        <!--[if lt IE 11]>
		<script src="/libs/html5shiv/3-7-0/html5shiv.js"></script>
		<script src="/libs/respond-js/1-4-2/respond.min.js"></script>
		<![endif]-->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="description" content="{{env('APP_DESCRIPTION')}}" />
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
        {{--  <script>
            (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:1629436,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
        </script>  --}}
        <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/assets/fonts/fontawesome/css/fontawesome-all.min.css">
        <link rel="stylesheet" href="/assets/plugins/animation/css/animate.min.css">
        <link rel="stylesheet" href="/assets/plugins/notification/css/notification.min.css">
        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="stylesheet" href="/css/custom.css">

        <script src="/vendor/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="/js/floating-wpp.min.js"></script>
        <link rel="stylesheet" href="/css/floating-wpp.min.css">
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="/js/script.js"></script>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-165374331-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'UA-165374331-1');
        </script>
        @yield('head')

    </head>

    <body>
        <div class="loader-bg">
            <div class="loader-track">
                <div class="loader-fill"></div>
            </div>
        </div>
        <nav class="pcoded-navbar">
            <div class="navbar-wrapper">
                <div class="navbar-brand header-logo">
                    <a href="/" class="b-brand">

                        <div class="">
                            <img src="/images/logo2.png" alt="logo" class="logo">
                        </div>
                        <span class="b-title">{{env('APP_NAME')}}</span>
                    </a>
                    <a class="mobile-menu" id="mobile-collapse"><span></span></a>
                </div>
                <div class="navbar-content scroll-div" style="overflow-y: auto;">
                    <ul class="nav pcoded-inner-navbar">
                        <li class="nav-item pcoded-menu-caption">
                            <label>Dashbord</label>
                        </li>

                        @if (Auth::user()->is_admin)
                        <li class="nav-item"><a href="/admin" class="nav-link"><span class="pcoded-micon"><i
                                        class="feather icon-home"></i></span><span class="pcoded-mtext">Admin
                                    Dashboard</span></a>
                        </li>
                        @endif

                        <li class="nav-item"><a href="{{request()->user->routePath()}}" class="nav-link"><span
                                    class="pcoded-micon"><i class="feather icon-home"></i></span><span
                                    class="pcoded-mtext">Dashboard</span></a>
                        </li>

                        <li class="nav-item pcoded-menu-caption">
                            <label>Navigation</label>
                        </li>

                        <li class="nav-item pcoded-hasmenu">
                            <a class=""><span class="pcoded-micon"><i class="fa fa-money-bill"></i></span><span
                                    class="pcoded-mtext">Wallet</span></a>
                            <ul class="pcoded-submenu">
                                <li class=""><a href="/user/wallet/{{request()->user->id}}/fund" class="">Top up</a>
                                </li>
                                <li class=""><a href="/user/wallet/{{request()->user->id}}/transfer"
                                        class="">Transfer</a>
                                </li>
                                @can('debit',request()->user)
                                <li class=""><a href="/user/wallet/{{request()->user->id}}/debit" class="">Debit</a>
                                </li>
                                @endcan
                                <li class=""><a href="/user/wallet/{{request()->user->id}}/history" class="">History</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item pcoded-hasmenu">
                            <a class=""><span class="pcoded-micon"><i class="fa fa-link"></i></span><span
                                    class="pcoded-mtext">Referral</span></a>
                            <ul class="pcoded-submenu">
                                <li class=""><a href="/user/referral/{{request()->user->id}}/withdraw"
                                        class="">Withdraw</a>
                                </li>
                                <li class=""><a href="/user/referral/{{request()->user->id}}/history"
                                        class="">History</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item pcoded-menu-caption">
                            <label>Bill Payment</label>
                        </li>

                        <li class="nav-item text-success"><a href="/user/{{request()->user->id}}/airtime/"
                                class="nav-link"><span class="pcoded-micon"><i class="fa fa-phone"></i></span><span
                                    class="pcoded-mtext">Airtime</span></a>
                        </li>
                        <li class="nav-item text-success"><a href="/user/{{request()->user->id}}/data"
                                class="nav-link"><span class="pcoded-micon"><i class="fa fa-globe"></i></span><span
                                    class="pcoded-mtext">Data</span></a>
                        </li>
                        <li class="nav-item text-success"><a href="/user/{{request()->user->id}}/cable"
                                class="nav-link"><span class="pcoded-micon"><i class="fa fa-tv"></i></span><span
                                    class="pcoded-mtext">Cable Tv</span></a>
                        </li>

                        <li class="nav-item pcoded-menu-caption">
                            <label>API</label>
                        </li>

                        <li class="nav-item text-success"><a href="/user/{{request()->user->id}}/api/documentation"
                                class="nav-link"><span class="pcoded-micon"><i class="fa fa-info"></i></span><span
                                    class="pcoded-mtext">Api Documentation</span></a>
                        </li>

                        <li class="nav-item pcoded-menu-caption">
                            <label>Administration</label>
                        </li>
                        @can('massMail',App\User::class)
                        <li class="nav-item"><a href="/user/{{request()->user->id}}/contact" class="nav-link"><span
                                    class="pcoded-micon"><i class="fa fa-envelope-square"></i></span><span
                                    class="pcoded-mtext">Send User
                                    Mail</span></a>
                        </li>
                        @endcan




                        <li class="nav-item pcoded-menu-caption">
                            <label>Subscription</label>
                        </li>
                        @if(request()->user->is_reseller)
                        <li class="nav-item text-success"><a href="/user/{{request()->user->id}}/subscriptions"
                                class="nav-link"><span class="pcoded-micon"><i
                                        class="fa fa-money-bill-alt"></i></span><span
                                    class="pcoded-mtext">History</span></a>
                        </li>
                        @endif

                        {{--   @json(request()->user->upgradeList())  --}}

                        @can('upgrade',request()->user)
                        {{--  @if(request()->user->lastSub())  --}}
                        <li class="nav-item text-success"><a href="/user/{{request()->user->id}}/subscription/upgrade"
                                class="nav-link text-danger"><span class="pcoded-micon"><i
                                        class="fa fa-sync"></i></span><span class="pcoded-mtext">Upgrade
                                    Now</span></a>
                        </li>
                        {{--   @endif  --}}
                        @endcan


                        {{-- <li class="nav-item pcoded-hasmenu">
                            <a class=""><span class="pcoded-micon"><i class="fa fa-money-bill"></i></span><span
                                    class="pcoded-mtext">Wallet</span></a>
                            <ul class="pcoded-submenu">
                                <li class=""><a href="/user/wallet/{{request()->user->id}}/history"
                        class="">History</a>
                        </li>
                        <li class=""><a href="/user/wallet/{{request()->user->id}}/fund" class="">Top up</a>
                        </li>
                    </ul>
                    </li> --}}

                    </ul>
                </div>
            </div>
        </nav>
        <header class="navbar pcoded-header navbar-expand-lg navbar-light">
            <div class="m-header">
                <a class="mobile-menu" id="mobile-collapse1"><span></span></a>
                <a href="/" class="b-brand">
                    <div class="">
                        <img src="/images/logo2.png" alt="logo" class="logo">
                    </div>
                    <span class="b-title">{{env('APP_NAME')}}</span>
                </a>
            </div>
            <a class="mobile-menu" id="mobile-header" href="#!">
                <i class="feather icon-more-horizontal"></i>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li><a href="#!" class="full-screen" onclick="javascript:toggleFullScreen()"><i
                                class="feather icon-maximize"></i></a></li>


                </ul>
                <ul class="navbar-nav ml-auto">
                    <li>
                        <div class="dropdown drp-user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon feather icon-settings"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-notification">
                                <div class="pro-head">
                                    <img src="{{request()->user->profilePic()}}" class="img-radius"
                                        alt="User-Profile-Image">
                                    <span>{{request()->user->full_name}} ({{request()->user->login}})</span>
                                    <a href="/logout" class="dud-logout" title="Logout">
                                        <i class="feather icon-log-out"></i>
                                    </a>
                                </div>
                                <ul class="pro-body">
                                    {{-- <li><a href="#!" class="dropdown-item"><i class="feather icon-settings"></i>
                                            Settings</a></li> --}}
                                    <li><a href="{{request()->user->routePath()}}/edit" class="dropdown-item"><i
                                                class="feather icon-user"></i> Profile</a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </header>

        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">
                <div class="pcoded-content">
                    <div class="pcoded-inner-content">
                        <div class="main-body">
                            <div class="page-wrapper">
                                {{-- @if(session('success'))
                                <div class="alert alert-success rounded">
                                    {{session('success')}}
                            </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-danger rounded">
                                {{session('error')}}
                            </div>
                            @endif --}}
                            {{-- {{request()->user->lastSub()}} --}}
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!--[if lt IE 11]>
        <div class="ie-warning">
            <h1>Warning!!</h1>
            <p>You are using an outdated version of Internet Explorer, please upgrade
               <br/>to any of the following web browsers to access this website.
            </p>
            <div class="iew-container">
                <ul class="iew-download">
                    <li>
                        <a href="http://www.google.com/chrome/">
                            <img src="../assets/images/browser/chrome.png" alt="Chrome">
                            <div>Chrome</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.mozilla.org/en-US/firefox/new/">
                            <img src="../assets/images/browser/firefox.png" alt="Firefox">
                            <div>Firefox</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.opera.com">
                            <img src="../assets/images/browser/opera.png" alt="Opera">
                            <div>Opera</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.apple.com/safari/">
                            <img src="../assets/images/browser/safari.png" alt="Safari">
                            <div>Safari</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="../assets/images/browser/ie.png" alt="">
                            <div>IE (11 & above)</div>
                        </a>
                    </li>
                </ul>
            </div>
            <p>Sorry for the inconvenience!</p>
        </div>
        <div class="floating-wpp"></div>
    <![endif]-->

        {{-- <script src="/assets/js/vendor-all.min.js"></script> --}}

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        {{-- <script src="/assets/js/menu-setting.min.js"></script> --}}
        <script src="/assets/js/pcoded.min.js"></script>
        {{-- 
        <script src="/assets/plugins/amchart/js/amcharts.js"></script>
        <script src="/assets/plugins/amchart/js/gauge.js"></script>
        <script src="/assets/plugins/amchart/js/serial.js"></script>
        <script src="/assets/plugins/amchart/js/light.js"></script>
        <script src="/assets/plugins/amchart/js/pie.min.js"></script>
        <script src="/assets/plugins/amchart/js/ammap.min.js"></script>
        <script src="/assets/plugins/amchart/js/usalow.js"></script>
        <script src="/assets/plugins/amchart/js/radar.js"></script>
        <script src="/assets/plugins/amchart/js/worldlow.js"></script>
        <script src="/assets/plugins/notification/js/bootstrap-growl.min.js"></script> --}}
        {{-- <script src="/assets/js/pages/dashboard-custom.js"></script> --}}
        @yield('js')

        <script>
            var alerted = localStorage.getItem('alerted');

            @if (env('GENERAL_ALERT'))
            
            if(!alerted){
            swal("",'{{env("GENERAL_ALERT")}}',{
            
            });
            localStorage.setItem('alerted',true);
            }
            

            @endif

            var dashbord = "{{request()->user->routePath()}}";
            @if (session('success'))
            
            swal("",'{{session('success')}}',"success",{
            buttons: ["Stay Here", "Dashbord"],
            //dangerMode: true,
            })
            .then((home) => {
            if (home) {
            
            location.replace(dashbord);
            } else {
            
            }
            });
            @endif
            
            @if (session('error'))
            
            swal("",'{{session('error')}}',"error",{
            buttons: ["Stay Here", "Dashboard"],
            dangerMode: true,
            })
            .then((home) => {
            if (home) {
            location.replace(dashbord);
            } else {
            
            }
            });
            @endif

            $( document ).ready(function() {
                wpChat();
               // $.stickysidebarscroll(".scroll-div",{offset: {top: 10, bottom: 200}});
                var url = window.location;
                var link =url.origin + url.pathname;

                var element = $('.nav-item a').filter(function() {
                   //console.log(this);
                   return this.href == link; //|| url.href.indexOf(this.href) == 0;
               });

                element.parent().addClass('active');

                //console.log(element);
                //console.log($('li.pcoded-hasmenu li.active a').parent().parent().parent());
                var e = $('li.pcoded-hasmenu li.active a').filter(function() {
                   
                    return this.href == link ;//|| url.href.indexOf(this.href) == 0;
                   
                });

                //console.log(e.children.length);

                e.parent().parent().parent().addClass('pcoded-trigger');

                $('.pcoded-trigger ul').attr('style','display:block');//.attr('style'));
            });
        </script>
        <center>
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright © <a href="/">{{env('APP_NAME')}}</a> {{date('Y')}}</span>
                    | <span>Developed by <a href="https://altechtic.com">Altechtic Solutions</a></span>
                </div>
            </div>
        </center>
        @if(!Auth::user()->is_admin && env('APP_ENV') =='production')
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
        @endif
    </body>

</html>