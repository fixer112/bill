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

        <!-- Start SmartBanner configuration -->
        <meta name="smartbanner:title" content="MoniWallet">
        <meta name="smartbanner:author" content="MoniWallet">
        <meta name="smartbanner:price" content="FREE">
        <meta name="smartbanner:price-suffix-apple" content=" - On the App Store">
        <meta name="smartbanner:price-suffix-google" content=" - In Google Play">
        {{--<meta name="smartbanner:icon-apple"
                    content="https://is4-ssl.mzstatic.com/image/thumb/Purple123/v4/17/96/c5/1796c5bb-fd24-d656-81e9-1819879a6053/AppIcon-0-1x_U007emarketing-0-0-GLES2_U002c0-512MB-sRGB-0-0-0-85-220-0-0-0-6.png/230x0w.jpg">  --}}
        <meta name="smartbanner:icon-google"
            content="http://lh3.ggpht.com/f4oX61ljZ6x8aYDELZOgxlvdUEu73-wSQ4fy5bx6fCRISnZP8T353wdaM43RO_DbGg=w300">
        <meta name="smartbanner:button" content="View">
        <meta name="smartbanner:button" content="DOWNLOAD">
        {{--   <meta name="smartbanner:button-url-apple" content="https://ios/application-url">  --}}
        <meta name="smartbanner:button-url-google"
            content="https://play.google.com/store/apps/details?id=com.altechtic.moniwallet.moniwallet">
        <meta name="smartbanner:enabled-platforms" content="android">
        <meta name="smartbanner:close-label" content="Close">
        <!-- End SmartBanner configuration -->

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

        <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" />
        {{--  <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet" />  --}}
        <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />

        <script src="/vendor/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="/js/floating-wpp.min.js"></script>
        <link rel="stylesheet" href="/css/floating-wpp.min.css">
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="/js/script.js"></script>


        <!--// https://firebase.google.com/docs/web/setup#available-libraries -->
        <script src="https://www.gstatic.com/firebasejs/7.15.1/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.15.1/firebase-analytics.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.15.1/firebase-messaging.js"></script>
        <script>
            @if(!Auth::user()->is_admin)
            localStorage.setItem('user_id', "{{request()->user->id}}");
            localStorage.setItem('user_token', "{{request()->user->api_token}}");
            @endif
            //console.log(localStorage.getItem('user_token'));
        </script>

        @if (!Auth::user()->is_admin)
        <link rel="stylesheet" href="/css/smartbanner.min.css">
        <script src="/js/smartbanner.min.js"></script>
        @endif


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
                                <li class=""><a href="/user/wallet/{{request()->user->id}}/fund" class="">Fund
                                        Wallet</a>
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
                                <li class=""><a href="/user/referral/{{request()->user->id}}/withdraw" class="">Withdraw
                                        To Wallet</a>
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
                        <li class="nav-item text-success"><a href="/user/{{request()->user->id}}/electricity"
                                class="nav-link"><span class="pcoded-micon"><i class="fa fa-bolt"></i></span><span
                                    class="pcoded-mtext">Electricity</span></a>
                        </li>
                        <li class="nav-item pcoded-hasmenu">
                            <a class=""><span class="pcoded-micon"><i class="fa fa-envelope-square"></i></span><span
                                    class="pcoded-mtext">Bulk SMS</span></a>
                            <ul class="pcoded-submenu">
                                <li class=""><a href="/user/{{request()->user->id}}/sms" class="">Compose
                                        SMS</a>
                                </li>
                                <li class=""><a href="/user/{{request()->user->id}}/sms/history" class="">History</a>
                                </li>
                                <li class=""><a href="/user/{{request()->user->id}}/sms/group/create" class="">Create
                                        Group</a>
                                </li>
                                <li class=""><a href="/user/{{request()->user->id}}/sms/group" class="">All Groups</a>
                                </li>
                            </ul>
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
        <![endif]-->
        <div class="floating-wpp"></div>

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
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.bootstrap.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
        @yield('js')


        <script>
            $(document).ready(function() {

            wpChat();
        });
            var alerted = localStorage.getItem('alerted');
            var timeout = localStorage.getItem('time');

            @if (env('GENERAL_ALERT'))
            
            if(!alerted || (timeout != '' && (new Date().getTime() - timeout)/1000/60 > 60)){
            swal("",`{{env("GENERAL_ALERT")}}`,{
            
            });
            localStorage.setItem('alerted',true);
            localStorage.setItem('time',Date.now());
            }
            

            @endif

            var dashbord = "{{request()->user->routePath()}}";
            @if (session('success'))
            
            swal("",`{{session('success')}}`,"success",{
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
            
            swal("",`{{session('error')}}`,"error",{
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
                var index = $('table.table').find('th:last').index();
                $('table.table').DataTable( {
                responsive: true,
                pageLength: 1000,
                "order": [
                [index, "desc"]
                ],
                dom: 'Bfrtip',
                buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
                ]
                });
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
        {{--  <script type="text/javascript">
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/5eb1911781d25c0e58490940/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
            })();
        </script>  --}}
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
            function showNumbers(numbers){
                swal("Phone Numbers",numbers,{
                //buttons: ["Stay Here", "Dashboard"],
                });
            }
        </script>
    </body>

</html>