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