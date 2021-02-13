<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Conciflex</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.png') }}">

        <!-- App css -->
        <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/jquery-ui.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/metisMenu.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/app.css')}}" rel="stylesheet" type="text/css" />
        @yield('headerStyle')
    </head>
@section('body')
@show
    <body class="account-body" style="background: #2D5275">

        <!-- content -->
        @yield('content')



        <!-- App js -->
        <!-- <script src="{{ URL::asset('assets/js/app.js') }}"></script> -->

    </body>

</html>
