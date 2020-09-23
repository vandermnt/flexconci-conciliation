@extends('layouts.authLayoutStone')
@section('headerStyle')
<link href="{{ URL::asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

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
                <p class="text-muted mb-0">Informe seu Stone Code abaixo e clique no botão Avançar</p>
              </div>

              <div class="form-horizontal auth-form" >
                <div class="user-thumb text-center m-b-40">
                  <img src="{{ URL::asset('assets/images/logo-stone.png')}}" style="width: 150px" alt="thumbnail">
                  <!-- <h5>{{Auth::user()->NOME }}</h5> -->
                </div><br>
                <div class="form-group">
                  <!-- <label for="userpassword">E-mail</label> -->
                  <div class="input-group mb-3">

                    <input type="text" class="form-control" name="password" id="email" placeholder="Digite o stone code" required>
                  </div>
                </div>


                <div class="form-group mb-0 row">
                  <div class="col-12 mt-2">
                    <button onclick="autorizacaoCielo()" class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light" style="background: #2D5275; color: white;"><b>AVANÇAR</b><i class="fas fa-sign-in-alt ml-1"></i></button>
                  </div><!--end col-->
                </div> <!--end form-group-->
              </div><!--end form-->
            </div><!--end /div-->

            <!-- <div class="m-3 text-center text-muted">
            <p class="">Not you ? return  <a href="/authentication/auth-register" class="text-primary ml-2">Sign In</a></p>
          </div> -->
        </div><!--end card-body-->
      </div><!--end card-->
    </div><!--end auth-page-->
  </div><!--end col-->
</div><!--end row-->
</div><!--end container-->
<!-- End Log In page -->
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

function autorizacaoCielo(){
  var email = document.getElementById("email").value;

  if(email == '' ){
    alert("Preencha o campo e-mail!");
  }else{

    // var settings = {
    //   url: "{{ url('teste') }}",
    //   method: "get"
    // };
    //
    // $.ajax(settings).done(function (response) {
    //   console.log(response);
    // });

    $.ajax({
      url: "{{ url('teste') }}",
      method: "get",
      success: function(response){
        console.log(response);
      }
    })

  }
}

</script>
@section('footerScript')
<script type="text/javascript">
$(document).ready(function() {
  document.body.classList.add('account-body');
  document.body.classList.add('accountbg');
});

</script>
@stop
