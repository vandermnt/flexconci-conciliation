@extends('layouts.authLayout')
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
                <p class="text-muted mb-0">Informe seu e-mail abaixo e clique no botão Avançar</p>
              </div>

              <div class="form-horizontal auth-form my-4" >
                <div class="user-thumb text-center m-b-30">
                  <img src="{{ URL::asset('assets/images/logo-cielo.png')}}" class=" thumb-xl" alt="thumbnail">
                  <!-- <h5>{{Auth::user()->NOME }}</h5> -->
                </div><br>
                <div class="form-group">
                  <!-- <label for="userpassword">E-mail</label> -->
                  <div class="input-group mb-3">

                    <input type="text" class="form-control" name="password" id="email" placeholder="Digite seu e-mail" required>
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
<script>

function autorizacaoCielo(){
  var email = document.getElementById("email").value;

  if(email == '' ){
    alert("Preencha o campo e-mail!");
  }else{
    localStorage.setItem('email', email);

    // window.location.href = 'https://digitalhml.hdevelo.com.br/oauth/?mode=redirect&client_id=0d873941-4ae6-3344-bd72-63f9ff4058a8&redirect_uri=http:%2F%2Flocalhost:8000&state=STATE_INFO&scope=profile_read,transaction_read';
    window.location.href = 'https://minhaconta2.cielo.com.br/oauth/?mode=redirect&client_id=0d873941-4ae6-3344-bd72-63f9ff4058a8&redirect_uri=http:%2F%2Flocalhost:8000/credeciamento&state=STATE_INFO&scope=profile_read,profile_write,transaction_read,transaction_write';

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
