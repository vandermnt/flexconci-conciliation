<!-- @extends('layouts.authLayout') -->
@section('headerStyle')

<link href="{{ URL::asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.4.4/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script> -->

@stop

@section('body')
<body class="bg-card">
  @stop
  @section('content')

  <div class="container-fluid">
    <!-- Log In page -->
    <div class="row vh-100">
      <div class="col-lg-3 pr-0">
        <div class="auth-page">
          <div class="card mb-0 shadow-none h-100">
            <div class="card-body">

              <div class="mb-12" style="text-align:center">
                <a class="logo logo-admin" >
                  <span><img src="{{ URL::asset('assets/images/logoconci.png')}}" height="45" class="my-3"></span>
                  <!-- <span><img src="{{ URL::asset('assets/images/logo-dark.png')}}" height="16" alt="logo" class="logo-lg logo-dark my-3"></span> -->
                  <!-- <span><img src="{{ URL::asset('assets/images/logo.png')}}" height="16" alt="logo" class="logo-lg logo-light my-3"></span> -->
                </a>
              </div>

              <div class="px-3">
                <h2 class="font-weight-semibold font-22 mb-2"> Seja bem-vindo!</h2>
                <p class="text-muted">Conciliação de cartões de crédito, débito, voucher e outros meios de pagamentos.</p>

                <!-- <ul class="nav-border nav nav-pills" role="tablist">
                <li class="nav-item">
                <a class="nav-link active font-weight-semibold" data-toggle="tab" href="#LogIn_Tab" role="tab">Log In</a>
              </li>
              <li class="nav-item">
              <a class="nav-link font-weight-semibold" data-toggle="tab" href="#Register_Tab" role="tab">Register</a>
            </li>
          </ul> -->

          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane active" id="LogIn_Tab" role="tabpanel">
              <form class="form-horizontal auth-form my-4" id="formLogin" name="formLogin">
                <!-- <form class="form-horizontal auth-form my-4" method="post" action="{{ route('loginlogin') }}"> -->

                @csrf

                <div class="form-group">
                  <label for="username">Usuário</label>
                  <div class="input-group mb-3">
                    <span class="auth-form-icon">
                        <i class="dripicons-user"></i>
                    </span>
                    <input type="text" class="form-control" name="user" id="username" placeholder="Usuário">
                  </div>
                </div><!--end form-group-->

                <div class="form-group">
                  <label for="userpassword">Senha</label>
                  <div class="input-group mb-3">
                    <span class="auth-form-icon">
                        <i class="dripicons-lock"></i>
                    </span>
                    <input type="password" class="form-control" name="password" id="userpassword" placeholder="Senha">
                  </div>
                </div><!--end form-group-->

                <div class="form-group row mt-4">
                  <div class="col-sm-6">
                    <!-- <div class="custom-control custom-switch switch-success">
                      <input type="checkbox" class="custom-control-input" id="customSwitchSuccess">
                      <label class="custom-control-label text-muted" for="customSwitchSuccess">Lembra-me</label>
                    </div> -->
                  </div><!--end col-->
                  <div class="col-sm-12 ">
                    <a href="auth-recover-pw." class="text-muted font-13"><i class="dripicons-lock"></i> Esqueceu sua senha?</a>
                  </div><!--end col-->
                </div><!--end form-group-->

                <div class="form-group mb-0 row">
                  <div class="col-12 mt-2">
                    <a id="submitFormLogin" type="submit" class="btn btn-gradient-primary btn-round btn-block" style="background: #2D5275; color: white" type="button">Log In <i class="fas fa-sign-in-alt ml-1"></i></a>
                    <!-- <button id="submitFormLogin" type="submit" class="btn btn-gradient-primary btn-round btn-block" style="background: #2D5275" type="button">Log In <i class="fas fa-sign-in-alt ml-1"></i></button> -->

                  </div><!--end col-->
                </div> <!--end form-group-->
              </form><!--end form-->

            </div>

          </div>
        </div>


      </div>
    </div>
  </div>
</div>
<div class="col-lg-9 p-0 h-100vh d-flex justify-content-center" style="background: url('assets/images/fundoLog.png'); background-size: cover;">
  <div class="align-items-center" >
    <div >
      <!-- <img src="{{ URL::asset('assets/images/logo-sm.png')}}" alt="" class="thumb-sm">
      <h4 class="mt-3 text-white">Welcome To <span class="text-warning">Metrica</span> </h4>
      <h1 class="text-white">Let's Get Started</h1>
      <p class="font-18 mt-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod.</p> -->
      <div class="border w-25 mx-auto border-warning"></div>
    </div>
  </div>
</div>
</div>
<!--
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
Launch static backdrop modal
</button> -->

<!-- Modal -->
<form id="form_modal_empresas" method="post" action="{{ route('loginglobal') }}">
  <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
  <input id="user_global" type ="hidden" name="usuario_global" value="">
  <input id="empresa_escolhida" type ="hidden" name="empresaescolhida" value="">


  <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div  class="modal-dialog">
      <div  class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" id="staticBackdropLabel" style="color: white">Empresas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div id="bodymodal" class="modal-body" style="max-height: 370px; overflow: auto">
          <h6> Pesquisa CNPJ: </h6>
          <input id="buscaEmpresa" onkeypress='return somenteNumero(event)'placeholder="Pesquise a empresa desejada pelo CNPJ"  onKeyDown="escolherCnpj()" style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px" class="form-control" name="cnpj">
          <h6> Pesquisa Nome: </h6>
          <input id="buscaEmpresaNome" placeholder="Pesquise a empresa desejada pelo NOME"  onKeyDown="escolherNome()" style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px" class="form-control" name="nome">
        </div>
        <!-- <div class="modal-footer" style="background: #2D5275"> -->
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-success"><b>ENTRAR</b></button>
        <!-- </div> -->
      </div>
    </div>
  </div>
</form>

<form id="form_modal_clientes" method="post" action="{{ route('logincomercial') }}">
  <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
  <input id="user_comercial" type ="hidden" name="usuario_comercial" value="">


  <div class="modal fade" id="modal_clientes" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modal_clientes" aria-hidden="true">
    <div  class="modal-dialog">
      <div  style="max-height:500px" class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title"  style="color: white">Clientes</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div id="bodymodalcliente" class="modal-body" >
          <h6> Escolha o cliente: </h6>
          <select style="margin-top: -5px; padding-left: 7px; padding-top: 2px; padding-bottom: 5px; height: 30px" id="combo_cliente" name="combo_cliente" class="custom-select" required>
            <option selected value=""> Selecione um cliente </option>
          </select>
        </div>
        <!-- <div class="modal-footer" style="background: #2D5275"> -->
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <button type="button" id="bt_form_clientes" onclick="submitFormModalClientes()" class="btn btn-success"><b>ENTRAR</b></button>
        <!-- </div> -->
      </div>
    </div>
  </div>
</form>

</div>
<script>

var clientes = null;

document.addEventListener('keypress', function (e) {
  if (e.key === 'Enter') {
    document.getElementById("submitFormLogin").click();
  }
});

$('#submitFormLogin').click(function(){
  autenticacao = {
    user: document.getElementById("username").value,
    password: document.getElementById("userpassword").value
  }
  var user = document.getElementById("username");
  $.ajax({
    url: "{{ route('loginlogin') }}",
    type: "post",
    header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: ({_token : $('meta[name="csrf-token"]').attr('content'), autenticacao}),
    dataType: 'json',
    success: function (response){
      if(response){

        if(response[1] == 'user_global'){
          bodymodal = document.getElementById("bodymodal");
          response[0].forEach(res => {

            var label_clientes = document.createElement("A");
            var div = document.createElement('div');

            // div.style.overflow = "scroll";
            label_clientes.innerHTML = res.NOME_FANTASIA;

            // label_clientes.setAttribute('name' , "array[]");
            label_clientes.setAttribute('value' , res.CODIGO);
            label_clientes.setAttribute('id' , res.CODIGO);

            div.setAttribute('class', "col-12");
            div.setAttribute('id', 'div'+res.CODIGO);

            label_clientes.addEventListener("click", function() {
              submitFormEmpresas(res.CODIGO, response[2].CODIGO);
            }, false);

            label_clientes.style = "display: none; margin-top: 10px";

            bodymodal.appendChild(div);
            div.appendChild(label_clientes);

            var teste = document.getElementById('div'+res.CODIGO);

            teste.addEventListener("mouseover", mouseOver);
            teste.addEventListener("mouseout", mouseOut);


            function mouseOver(){
              teste.style = "background: #2D5275; color: white";
            }

            function mouseOut(){
              teste.style = "background: white; color: #2D5275";
            }
          });

          clientes = response[0];

          $("#staticBackdrop").modal({
            show: true
          });
        }else if(response[1] == 'user_comercial'){

          var select = document.getElementById("combo_cliente");

          document.getElementById("user_comercial").value = response[2].CODIGO;

          response[0].forEach((response) => {
            var opt = response.NOME;
            var el = document.createElement("option");
            el.textContent = opt;
            el.value = response.CODIGO;
            select.appendChild(el);

          });

          $("#modal_clientes").modal({
            show: true
          });
        }else if(response[1] == 'user_comum'){

          window.location.href = "{{ url('/ ')}}";
        }

      }else{
        window.location.href = "{{ url('/ ')}}";
      }
    }
  });
});

function submitFormEmpresas(empresaEscolhida, usuarioLogin){
  console.log(usuarioLogin);
  document.getElementById("empresa_escolhida").value = empresaEscolhida;
  document.getElementById("user_global").value = usuarioLogin;
  document.getElementById("form_modal_empresas").submit();
  return
}

function submitFormModalClientes(){
  document.getElementById("form_modal_clientes").submit();
  return
}

function escolherCnpj(){
  setTimeout(function () {
    var val_input = document.getElementById("buscaEmpresa").value.toUpperCase();

    if(val_input == ""){
      clientes.forEach((cliente) => {

        document.getElementById(cliente.CODIGO).style.display = "none";

      });
    }else{
      clientes.forEach((cliente) => {

        var regex = new RegExp(val_input);

        resultado_cnpj = cliente.CPF_CNPJ.match(regex);

        if(resultado_cnpj) {
          document.getElementById(cliente.CODIGO).style.display = "block";
        }else{
          document.getElementById(cliente.CODIGO).style.display = "none";
        }
      });
    }
  }, 300);
}

function escolherNome(){
  setTimeout(function () {
    var val_input = document.getElementById("buscaEmpresaNome").value.toUpperCase();

    if(val_input == ""){
      clientes.forEach((cliente) => {

        document.getElementById(cliente.CODIGO).style.display = "none";

      });
    }else{
      clientes.forEach((cliente) => {

        var regex = new RegExp(val_input);

        resultado_nome = cliente.NOME_FANTASIA.match(regex);

        if(resultado_nome) {
          document.getElementById(cliente.CODIGO).style.display = "block";
        }else{
          document.getElementById(cliente.CODIGO).style.display = "none";
        }
      });
    }
  }, 300);
}

function somenteNumero(e){
  var tecla=(window.event)?event.keyCode:e.which;
  if((tecla>47 && tecla<58)) return true;
  else{
    if (tecla==8 || tecla==0) return true;
     else  return false;
  }
}

</script>
@endsection

@section('footerScript')
<script type="text/javascript">
//  $( document ).ready() block.
$(document).ready(function() {
  document.body.classList.add('bg-card');
});


</script>
@stop
