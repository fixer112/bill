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

        <script>
            (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:1629436,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
        </script>
        <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/assets/fonts/fontawesome/css/fontawesome-all.min.css">
        <link rel="stylesheet" href="/assets/plugins/animation/css/animate.min.css">
        <link rel="stylesheet" href="/assets/plugins/notification/css/notification.min.css">
        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="stylesheet" href="/css/custom.css">
        <script src="/js/script.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="/assets/js/vendor-all.min.js"></script>

        <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" />
        {{--  <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
          --}}
        <link href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css" rel="stylesheet" />
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

                        <li class="nav-item"><a href="/admin" class="nav-link"><span class="pcoded-micon"><i
                                        class="feather icon-home"></i></span><span
                                    class="pcoded-mtext">Dashboard</span></a>
                        </li>

                        <li class="nav-item pcoded-menu-caption">
                            <label>History</label>
                        </li>

                        <li class="nav-item"><a href="/admin/wallet/history" class="nav-link"><span
                                    class="pcoded-micon"><i class="fa fa-money-bill"></i></span><span
                                    class="pcoded-mtext">Wallet</span></a>
                        </li>

                        <li class="nav-item"><a href="/admin/wallet/referral" class="nav-link"><span
                                    class="pcoded-micon"><i class="fa fa-money-bill"></i></span><span
                                    class="pcoded-mtext">Referral</span></a>
                        </li>

                        <li class="nav-item"><a href="/admin/subscriptions" class="nav-link"><span
                                    class="pcoded-micon"><i class="fa fa-money-bill"></i></span><span
                                    class="pcoded-mtext">Subscription</span></a>
                        </li>

                        <li class="nav-item pcoded-menu-caption">
                            <label>Search</label>
                        </li>

                        @can('viewAny',App\User::class)
                        <li class="nav-item"><a href="/admin/search/users" class="nav-link"><span
                                    class="pcoded-micon"><i class="fa fa-users"></i></span><span
                                    class="pcoded-mtext">Users</span></a>
                        </li>
                        @endcan

                        <li class="nav-item pcoded-menu-caption">
                            <label>Administration</label>
                        </li>
                        @can('manageRoles',App\User::class)
                        <li class="nav-item"><a href="/admin/user/create" class="nav-link"><span class="pcoded-micon"><i
                                        class="fa fa-user"></i></span><span class="pcoded-mtext">Create
                                    Admin</span></a>
                        </li>
                        @endcan
                        @can('massMail',App\User::class)
                        <li class="nav-item"><a href="/admin/contact" class="nav-link"><span class="pcoded-micon"><i
                                        class="fa fa-envelope-square"></i></span><span class="pcoded-mtext">Send Mass
                                    Mail</span></a>
                        </li>

                        <li class="nav-item"><a href="/admin/sms" class="nav-link"><span class="pcoded-micon"><i
                                        class="fa fa-envelope-square"></i></span><span class="pcoded-mtext">Send Mass
                                    SMS/APP</span></a>
                        </li>
                        @endcan



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
                    {{-- <div class="b-bg">
                        <i class="feather icon-trending-up"></i>
                    </div>
                    <span class="b-title">{{env('APP_NAME')}}</span> --}}
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
                                    <img src="{{Auth::user()->profilePic()}}" class="img-radius"
                                        alt="User-Profile-Image">
                                    <span>{{Auth::user()->full_name}} ({{Auth::user()->login}})</span>
                                    <a href="/logout" class="dud-logout" title="Logout">
                                        <i class="feather icon-log-out"></i>
                                    </a>
                                </div>
                                <ul class="pro-body">
                                    {{-- <li><a href="#!" class="dropdown-item"><i class="feather icon-settings"></i>
                                            Settings</a></li> --}}
                                    <li><a href="/user/{{Auth::id()}}/edit" class="dropdown-item"><i
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
                            {{-- {{Auth::user()->lastSub()}} --}}
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
            var dashboard = "{{Auth::user()->routePath()}}";
            
            //console.log(dashboard);

            @if (session('success'))
            
            swal("",`{{session('success')}}`,"success",{
            buttons: ["Stay Here", "Dashbord"],
            //dangerMode: true,
            })
            .then((home) => {
            if (home) {
            
            location.replace(dashboard);
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
            location.replace(dashboard);
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
    </body>

</html>