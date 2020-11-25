@extends('layouts.authLayout')
@section('headerStyle')
<link href="{{ URL::asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />

@stop

@section('content')


<div class="container">
  <div class="row vh-100 ">
    <div class="col-12 align-self-center">
      <div class="auth-page">
        <div class="card auth-card shadow-lg">
          <div class="card-body">
            <div class="px-3">
              <div class="text-center auth-logo-text">
                <h4 class="mt-0 mb-3 mt-5">Autorização de Acesso</h4>
                <!-- <p class="text-muted mb-0">Informe seu e-mail abaixo e clique no botão Avançar</p> -->
              </div>

              <div class="form-horizontal auth-form my-4" >
                <div class="user-thumb text-center m-b-30">
                  <img src="{{ URL::asset('assets/images/logo-cielo.png')}}" class=" thumb-xl" alt="thumbnail">
                  <!-- <h5>{{Auth::user()->NOME }}</h5> -->
                </div><br>
                <div class="text-center">
                  <div id="preloader" style="display: none" class="loadercielo"></div>

                  <!-- <label for="userpassword">E-mail</label> -->
                  <p class="" id="msg"><b> </b></p>
                </div>
                <div class="form-group mb-0 row">
                  <div class="col-12 mt-2">
                    <a href="{{ url('/') }}"><button class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light" style="background: #2D5275; color: white;"><b>AVANÇAR</b><i class="fas fa-sign-in-alt ml-1"></i></button></a>
                  </div><!--end col-->
                </div> <!--end form-group-->
              </div><!--end form-->
            </div><!--end /div-->
          </div><!--end card-body-->
        </div><!--end card-->
      </div><!--end auth-page-->
    </div><!--end col-->
  </div><!--end row-->
</div><!--end container-->
<!-- End Log In page -->
<!-- <script type="text/javascript" src="assets/js/autorizacao-cielo2.js">  </script> -->
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
$(window).on("load", function () {
  const url = window.location.href;
  if (url.indexOf("code") != -1) {
    code = url.split("=");
    email = localStorage.getItem("email");

    document.getElementById("preloader").style.display = "block";

    $.ajax({
      url: "https://api2.cielo.com.br/consent/v1/oauth/access-token",
      type: "post",
      beforeSend: function (xhr) {
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader(
          "Authorization",
          "Basic " +
          "MmNkNzFmOGUtYWFmMS0zZDdhLWIxMzktODAxN2I0Y2QwMWYxOmIyNjNjYTg2LWUwMGUtMzA3My04MWIyLTRmNjk1ZWJlZGUzYQ=="
        );
      },
      data: JSON.stringify({"grant_type":"authorization_code","code":code[2]}),
      dataType: "json",
      success: function (response) {
        console.log(response)
        acess_token = response["access_token"];
        refresh_token = response["refresh_token"];

        array = [
          "SELL",
          "PAYMENT",
          "ANTECIPATION_CIELO",
          "ASSIGNMENT",
          "BALANCE",
          "ANTECIPATION_ALELO",
        ];
        $.ajax({
          url: "https://api2.cielo.com.br/edi-api/v2/edi/registers",
          // url: "https://apihom-cielo.sensedia.com/edi-api/v2/edi/registers",
          type: "post",
          beforeSend: function (xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + acess_token);
          },
          data: JSON.stringify({
            type: array,
            merchantEMail: email,
          }),
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          success: function (response) {
            registerID = response["registerID"];
            merchants = response["merchants"];
            mainMerchantId = response["mainMerchantId"];
            codigo = code[2];

            $.ajax({
              url: "{{ url('credenciamento-edi') }}",
              type: "post",
              header: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
              },
              data: {
                _token: "{{csrf_token()}}",
                registerID,
                email,
                acess_token,
                refresh_token,
                codigo,
                mainMerchantId,
                merchants,
              },
              dataType: "json",
              success: function (response) {
                console.log(response);
                document.getElementById("msg").style = "color: green";
                document.getElementById("msg").innerHTML = "<b>Credenciamento feito com sucesso!</b>";
              },
            });
          },
          error: function(response){
            console.log(response);
            document.getElementById("preloader").style.display = "none";
            document.getElementById("msg").style = "color: red";
            document.getElementById("msg").innerHTML = "<b>"+response.responseJSON.message+"</b>";
          }
        });
      },
      error: function (response) {
        console.log(response);
      }
    })
  }
});

</script>

@section('footerScript')

<script type="text/javascript">

$(document).ready(function() {
  document.body.classList.add('account-body');
  document.body.classList.add('accountbg');
});

</script>

@stop
