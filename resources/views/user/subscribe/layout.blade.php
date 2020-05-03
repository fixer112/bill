<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
        <link href="images/favicon.png" rel="icon" />

        <title>@yield('title') | {{env('APP_NAME')}} </title>
        <meta name="description" content="@yield('desc',env('APP_DESCRIPTION'))">
        <meta name="author" content="{{env('APP_NAME')}}">
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
        <link rel="stylesheet" type="text/css" href="/vendor/bootstrap/css/bootstrap.min.css" />

        {{-- <link rel="stylesheet" type="text/css" href="/vendor/font-awesome/css/all.min.css" />
        <link rel="stylesheet" type="text/css" href="/vendor/owl.carousel/assets/owl.carousel.min.css" />
        <link rel="stylesheet" type="text/css" href="/vendor/owl.carousel/assets/owl.theme.default.min.css" /> --}}
        <link rel="stylesheet" type="text/css" href="/css/stylesheet.css" />
        <script src="/js/script.js"></script>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-165374331-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'UA-165374331-1');
        </script>



    </head>

    <body>
        <section class="container">
            <div class="col-md-9 mx-auto">
                <div class=" bg-light shadow-md rounded p-3 m-5">
                    @yield('content')
                </div>
        </section>
    </body>

</html>