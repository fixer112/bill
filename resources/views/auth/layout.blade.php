<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="images/favicon.png" rel="icon" />
        <title>@yield('title') | {{env('APP_NAME')}} </title>
        <meta name="description" content="@yield('desc',env('APP_DESCRIPTION'))">
        <meta name="author" content="Altechtic Solutions">
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

        <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
        {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> --}}
        <link rel="stylesheet" type="text/css" href="/vendor/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="/css/login.css">
        <style>
            .brand-wrapper .logo {
                height: auto;
            }
        </style>
    </head>

    <body>
        <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
            <div class="container">
                <div class="card login-card">

                    <div class="row no-gutters">

                        <div class="col-md-5">
                            <img src="/images/login.jpg" alt="login" class="login-card-img">
                        </div>
                        <div class="col-md-7">

                            <div class="card-body">
                                <div class="brand-wrapper">
                                    <img src="/images/logo.png" alt="logo" class="logo">
                                </div>

                                <div class="card-body">
                                    @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                    @endif

                                    @if(session('success'))
                                    <div class="alert alert-success rounded mt-3 text-center">
                                        {{session('success')}}
                                    </div>
                                    @endif

                                    @if(session('error'))
                                    <div class="alert alert-danger rounded mt-3 text-center">
                                        {{session('error')}}
                                    </div>
                                    @endif

                                    @yield('content')

                                    <nav class="login-card-footer-nav">
                                        <a href="/terms">Terms of us.</a>
                                        <a href="/privacy">Privacy policy</a>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    </body>

</html>