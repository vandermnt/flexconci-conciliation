<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Conciflex</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="Conciliação de cartões de crédito, débito e outros meios de pagamentos" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.png') }}">

        <!-- <script type="module" src = "../node_modules/table-dragger/dist/table-dragger.min.js "> </script> -->


        <!-- App css -->
        <link href="{{ URL::asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/jquery-ui.min.css')}}" rel="stylesheet">
        <link href="{{ URL::asset('assets/css/metisMenu.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
				<link href="{{ URL::asset('assets/css/analytics-master.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/topbar.css')}}" rel="stylesheet" type="text/css" />

        @yield('headerStyle')
    </head>

    <body>

         <!-- leftbar -->
        <!-- @include('layouts/partials/sidebar/analytics-leftbar') -->

         <!-- toptbar -->
        @include('layouts/partials/topbar')

        <div class="page-wrapper" style="margin-top: 50px">

            <!-- Page Content-->
            <div class="page-content-tab">

             <!-- content -->
             @yield('content')

             <!-- extra Modal -->
             @include('layouts/partials/extra-modal')

              <!-- Footer -->
              @include('layouts/partials/footer')

            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

        <!-- jQuery  -->
        <script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/jquery-ui.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/metismenu.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/waves.js') }}"></script>
        <script src="{{ URL::asset('assets/js/feather.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ URL::asset('plugins/apexcharts/apexcharts.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.2/dist/table-dragger.min.js"></script>

        <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.4.1/prism.min.js"></script> -->

        <!-- App js -->
        <script src="{{ URL::asset('assets/js/app.js') }}"></script>
				<script src="{{ URL::asset('assets/js/topbar/submenu.js') }}"></script>
        <script src="https://kit.fontawesome.com/9a0b64c7c3.js" crossorigin="anonymous"></script>
        <!-- footerScript -->
        @yield('footerScript')
    </body>
</html>
