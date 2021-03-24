
<!-- Top Bar Start -->
<div class="topbar">
  <!-- Navbar -->
  <nav class="navbar-custom">
    <ul class="list-unstyled topbar-nav float-right mb-0">
      @if(Auth::user()->USUARIO_GLOBAL === 'S')
      <li class="dropdown">
        <a id="dropdownCadastros" class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
        aria-haspopup="false" aria-expanded="false">
        <span class="ml-1 nav-user-name hidden-sm"> Administrativo <i class="mdi mdi-chevron-down"></i> </span>
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownCadastros">
        <a class="dropdown-item" href="#"> Conciliação Automática </a>
        <a class="dropdown-item" href="/adm/desconciliacao"> Desconciliação Automática </a>
      </div>
    </li>
    <li class="dropdown">
      <a id="dropdownCadastros" class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
      aria-haspopup="false" aria-expanded="false">
      <span class="ml-1 nav-user-name hidden-sm"> Cadastros <i class="mdi mdi-chevron-down"></i> </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownCadastros">
      <a class="dropdown-item" href="{{ url('cadastro-adquirente')}}"> Operadoras </a>
      <a class="dropdown-item" href="{{ url('cadastro-banco')}}"> Bancos </a>
      <a class="dropdown-item" href="{{ url('cadastro-bandeira')}}"> Bandeiras </a>
      <a class="dropdown-item" href="{{ url('cadastro-taxa')}}"> Taxas </a>
    </div>
  </li>
  @endif
  <li class="hidden-sm">
    <a class="nav-link" data-toggle="modal" data-target="#chamado" style="color: white; cursor: pointer">
      Abrir chamado
    </a>
  </li>
  <li class="hidden-sm">
    <a class="nav-link dropdown-toggle waves-effect" data-toggle="dropdown" href="javascript: void(0);" role="button"
    aria-haspopup="false" aria-expanded="false" style="font-size: 25px">
    |
  </a>
</li>

{{-- <li class="dropdown notification-list"  style="color: white;  z-index:999">
  <a class="nav-link dropdown-toggle" onclick="checkNotification()" data-toggle="dropdown" href="www.google.com" role="button"
  aria-haspopup="false" aria-expanded="false">
  <i class="ti-bell noti-icon"  style="color: white"></i>
  @if(isset($qtde_projetos))
  @if($qtde_projetos>0)
  <span id="notification" class="badge badge-danger badge-pill noti-icon-badge">{{ $qtde_projetos }}</span>
  @endif
  @endif
</a>
<div class="dropdown-menu dropdown-menu-right dropdown-lg pt-0">

  <h6 class="dropdown-item-text font-15 m-0 py-3 text-white d-flex justify-content-between align-items-center" style="background: #084B8A">
    NOTIFICAÇÕES
    @if(isset($qtde_projetos))
    @if($qtde_projetos>0)
    <span class="badge badge-light badge-pill">{{ $qtde_projetos }}</span>
    @endif
    @endif
  </h6>
  <div class="snotification-list">
    <!-- <div class="slimscroll notification-list"> -->

    @if(isset($qtde_projetos) && $qtde_projetos > 0)
    <a href="{{ url('/lista-projetos') }}" class="dropdown-item py-3">
      <!-- <small class="float-right text-muted pl-2">2 min ago</small> -->
      <div class="media">
        <div class="avatar-md bg-primary">
          <i style="color: white" class="fas fa-project-diagram"></i>
        </div>
        <div class="media-body align-self-center ml-2 text-truncate">
          <h6 class="text-white d-flex justify-content-between align-items-center">PROJETOS <span class="badge badge-light badge-pill">{{ $qtde_projetos }}</span></h6>

          <!-- <h6 class="dropdown-item-text font-15 m-0 py-3 bg-primary text-white d-flex justify-content-between align-items-center">
          NOTIFICAÇÕES <span class="badge badge-light badge-pill">{{ $qtde_projetos }}</span>
        </h6>
        <small class="" style="color: white">Breve descrição</small> -->
      </div>
    </div>
  </a>
  @endif

</div>

</div>
</li> --}}
{{-- <li class="hidden-sm">
  <a class="nav-link dropdown-toggle waves-effect" data-toggle="dropdown" href="javascript: void(0);" role="button"
  aria-haspopup="false" aria-expanded="false" style="font-size: 25px">
  |
</a>
</li> --}}
<li class="dropdown">
  <a id="dropdownUserSettings" class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
  aria-haspopup="false" aria-expanded="false">
  {{-- <img src="{{ URL::asset('assets/images/users/user-4.jpg')}}" alt="profile-user" class="rounded-circle" /> --}}
  <!-- <span class="ml-1 nav-user-name hidden-sm">{{Session::get('codigologin') }} <i class="mdi mdi-chevron-down"></i> </span> -->
  <?php $primeiro_nome = explode(' ', Auth::user()->NOME); ?>
  <span class="ml-1 nav-user-name hidden-sm">{{$primeiro_nome[0] }} |   {{ Session::get('nome_fantasia')}} <i class="mdi mdi-chevron-down"></i> </span>
  <input type="hidden" name="usuario" value="{{ Auth::user()->CODIGO }}">
</a>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownUserSettings">
  {{-- <a class="dropdown-item" href="#"><i class="dripicons-user mr-2"></i> Editar Perfil</a>
  <a class="dropdown-item" href="#"><i class="dripicons-wallet mr-2"></i> Configurações</a>
  <a class="dropdown-item" href="#"><i class="dripicons-gear mr-2"></i> Ajuda</a> --}}
  {{-- <a class="dropdown-item" href="#"><i class="dripicons-lock text-muted mr-2"></i> Sair</a> --}}
  {{-- <div class="dropdown-divider"></div> --}}
  <a class="dropdown-item" href="{{ url('/logout') }}"><i class="dripicons-exit mr-2"></i> Sair </a>
  @if(Auth::user()->USUARIO_GLOBAL === 'S')
  <a class="dropdown-item" data-toggle="modal" data-target="#troca_cliente"><i class="dripicons-clockwise mr-2"></i> Trocar Empresa </a>
  @endif
</div>
</li>
<!-- <li class="mr-2">
<a href="#" class="nav-link" data-toggle="modal" data-animation="fade" data-target=".modal-rightbar">
<i data-feather="align-right" class="align-self-center"></i>
</a>
</li> -->
</ul>

<ul class="list-unstyled topbar-nav mb-0">
  <!-- <li>
  <a href="/crm/crm-index">
  <span class="responsive-logo">
  <img src="{{ URL::asset('assets/images/logo-sm.png')}}" alt="logo-small" class="logo-sm align-self-center" height="34">
</span>
</a>
</li> -->
<li style="margin-left: 30px; margin-top: -3px; ">
  <a class="nav-link" href="{{ url('/')  }}">
    <img src="{{ URL::asset('assets/images/logconcibr.png')}}" style="width: 130px;" alt="">
  </a>
</li>
<!-- <li>
<a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
aria-haspopup="false" aria-expanded="false">
<span class="ml-1 nav-user-name hidden-sm">Antecipacao <i class="mdi mdi-chevron-down"></i> </span>
</a>
<div class="dropdown-menu dropdown-menu-left" style="background: #2D5275;">
<a style="color: white" class="dropdown-item"  href="/anticipation"> Antecipacao de Vendas</a>
</div>
</li> -->
<!-- <li>
<a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
aria-haspopup="false" aria-expanded="false">
<span class="ml-1 nav-user-name hidden-sm">Dashboard <i class="mdi mdi-chevron-down"></i> </span>
</a>
<div class="dropdown-menu dropdown-menu-left" style="background: #2D5275;">
<a style="color: white" class="dropdown-item" href="#"> Gerencial</a>
<a style="color: white" class="dropdown-item" href="#"> Diagnóstico Financeiro </a>

</div>
</li>

<li>
<a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
aria-haspopup="false" aria-expanded="false">
<span class="ml-1 nav-user-name hidden-sm">Conciliação <i class="mdi mdi-chevron-down"></i> </span>
</a>
<div class="dropdown-menu dropdown-menu-left" style="background: #2D5275;">
<a style="color: white" class="dropdown-item" href="#"> Conferência Manual de Vendas</a>
<a style="color: white" class="dropdown-item" href="#"> Conferência Automática de Vendas </a>
<a style="color: white" class="dropdown-item" href="#"> Conciliação de Pagamentos </a>
<a style="color: white" class="dropdown-item" href="#"> Conciliação Bancária </a>
<a style="color: white" class="dropdown-item" href="#"> Conciliação de Taxas </a>
<a style="color: white" class="dropdown-item" href="#"> Conciliação de Aluguel e Outras Despesas </a>

</div>
</li>

<li>
<a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
aria-haspopup="false" aria-expanded="false">
<span class="ml-1 nav-user-name hidden-sm">Vendas <i class="mdi mdi-chevron-down"></i> </span>
</a>
<div class="dropdown-menu dropdown-menu-left" style="background: #2D5275;">
<a style="color: white" class="dropdown-item" href="/vendasoperadoras"> Vendas Operadoras</a>
<a style="color: white" class="dropdown-item" href="#"> Vendas sistema de gestão (ERP) </a>
</div>
</li>

<li>
<a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
aria-haspopup="false" aria-expanded="false">
<span class="ml-1 nav-user-name hidden-sm">Recebimentos <i class="mdi mdi-chevron-down"></i> </span>
</a>
<div class="dropdown-menu dropdown-menu-left" style="background: #2D5275;">
<a style="color: white" class="dropdown-item" href="#"> Recebimentos Operadoras</a>
<a style="color: white" class="dropdown-item" href="#"> Recebimentos Antecipados </a>
<a style="color: white" class="dropdown-item" href="#"> Despesas Extras (DOC/TEC/Aluguel/Outras/Tarifas)</a>
<a style="color: white" class="dropdown-item" href="#"> Previsão de Recebimentos Futuros</a>


</div>
</li> -->
<!-- <li>
<a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
aria-haspopup="false" aria-expanded="false">
<span class="ml-1 nav-user-name hidden-sm">Antecipacao <i class="mdi mdi-chevron-down"></i> </span>
</a>
<div class="dropdown-menu dropdown-menu-left">
<a class="dropdown-item" href="#"> Antecipacao de Vendas</a>
</div>
</li> -->
<!-- <li class="dropdown">
<a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
aria-haspopup="false" aria-expanded="false">
<span class="ml-1 p-2 bg-soft-classic nav-user-name hidden-sm rounded">System <i class="mdi mdi-chevron-down"></i> </span>
</a>
<div class="dropdown-menu dropdown-xl dropdown-menu-left p-0">
<div class="row no-gutters">
<div class="col-12 col-lg-6">
<div class="text-center system-text">
<h4 class="text-white">The Poworfull Dashboard</h4>
<p class="text-white">See all the pages Metrica.</p>
<a href="https://themesbrand.com/metrica/" class="btn btn-sm btn-pink mt-2">See Dashboard</a>
</div>
<div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
<div class="carousel-inner">
<div class="carousel-item active">
<img src="{{ URL::asset('assets/images/dashboard/dash-1.png')}}" class="d-block img-fluid" alt="...">
</div>
<div class="carousel-item">
<img src="{{ URL::asset('assets/images/dashboard/dash-4.png')}}" class="d-block img-fluid" alt="...">
</div>
<div class="carousel-item">
<img src="{{ URL::asset('assets/images/dashboard/dash-2.png')}}" class="d-block img-fluid" alt="...">
</div>
<div class="carousel-item">
<img src="{{ URL::asset('assets/images/dashboard/dash-3.png')}}" class="d-block img-fluid" alt="...">
</div>
</div>
</div>
</div>
<div class="col-12 col-lg-6">
<div class="divider-custom mb-0">
<div class="divider-text bg-light">All Dashboard</div>
</divi>
<div class="p-4">
<div class="row">
<div class="col-6">
<a class="dropdown-item mb-2" href="/analytics/analytics-index"> Analytics</a>
<a class="dropdown-item mb-2" href="/crypto/crypto-index"> Crypto</a>
<a class="dropdown-item mb-2" href="/crm/crm-index"> CRM</a>
<a class="dropdown-item" href="/projects/projects-index"> Project</a>
</div>
<div class="col-6">
<a class="dropdown-item mb-2" href="/ecommerce/ecommerce-index"> Ecommerce</a>
<a class="dropdown-item mb-2" href="/helpdesk/helpdesk-index"> Helpdesk</a>
<a class="dropdown-item" href="/hospital/hospital-index"> Hospital</a>
</div>
</div>
</div>
</div>
</div>
</div>
</li> -->
<!-- <li class="hide-phone app-search">
<form role="search" class="">
<input type="text" id="AllCompo" placeholder="Search..." class="form-control">
<a href=""><i class="fas fa-search"></i></a>
</form>
</li> -->
</ul>
</nav>
<!-- end navbar-->
</div>

<div class="topbarr submenu" style="margin-top: 70px;">
  <nav class="navbar-custom" style="background: white; min-height: 50px !important; border-bottom: 2px solid #2d5275">
    <ul class="list-unstyled topbar-nav mb-0" style="margin-left: 30px">
      <li>
        <a id="itemMenu" class="submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user"  href="{{ url('/') }}" role="button">
          <span class="ml-1 nav-user-name hidden-sm"><i class="fas fa-chart-bar"></i> Dashboard </span>
        </a>
        <!-- <div class="dropdown-menu dropdown-menu-left" style="background: white;">
        <a  class="dropdown-item" href="{{ url('/') }}"> Gerencial</a>
        <a  class="dropdown-item" href="#"> Diagnóstico Financeiro </a>
      </div> -->
    </li>

    <li>
      <a id="itemMenu" class="submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ route('conciliacao-vendas') }}" role="button"
      aria-haspopup="false" aria-expanded="false">
      <span class="ml-1 nav-user-name hidden-sm"><i class="far fa-handshake"></i> Conciliação de Vendas </span>
    </a>
    <!-- <div class="dropdown-menu dropdown-menu-left" style="background: white;">
    <a style="" class="dropdown-item" href="#"> Conferência Manual de Vendas</a>
    <a style="" class="dropdown-item" href="{{ url('/conciliacao-automatica') }}"> Conciliação Automática de Vendas </a>
    <a style="" class="dropdown-item" href="#"> Conciliação de Pagamentos </a>
    <a style="" class="dropdown-item" href="{{ url('/conciliacao-bancaria') }}"> Conciliação Bancária </a>
    <a style="" class="dropdown-item" href="#"> Conciliação de Taxas </a>
    <a style="" class="dropdown-item" href="#"> Conciliação de Aluguel e Outras Despesas </a>

  </div> -->
</li>

<li>
  <a id="itemMenu" class="submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ url('/vendas-sistema-erp')}}" role="button">
    <span dropzone=""class="ml-1 nav-user-name hidden-sm"><i class="fas fa-laptop"></i> Vendas ERP </span>
  </a>
</li>

<li>
  <a id="itemMenu" class="submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ url('/vendas-operadoras') }}" role="button">
    <span dropzone=""class="ml-1 nav-user-name hidden-sm"><i class="fas fa-money-check-alt"></i> Vendas Operadoras </span>
  </a>
</li>

<li>
  <a id="itemMenu" class="submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ url('/recebimentos-operadoras') }}" role="button">
    <span class="ml-1 nav-user-name hidden-sm"><i class="fas fa-donate"></i> Recebimentos & Despesas</span>
    <!-- <div class="dropdown-menu dropdown-menu-left" style="background: white;">
    <a style="" class="dropdown-item" href="{{ url('/recebimentos-operadora') }}"> Recebimentos Operadoras</a>
    <a style="" class="dropdown-item" href="#"> Recebimentos Antecipados </a>
    <a style="" class="dropdown-item" href="#"> Despesas Extras (DOC/TEC/Aluguel/Outras/Tarifas)</a>
    <a style="" class="dropdown-item" href="{{ url('/previsao-recebimentos') }}"> Previsão de Recebimentos Futuros</a>
    <a style="" class="dropdown-item" href="{{ url('/antecipacao')}}"> Antecipação Trava Livre</a>
    =======
    <a id="itemMenu" class="nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ url('/recebimentos-operadoras') }}" role="button">
    <span class="ml-1 nav-user-name hidden-sm"><i class="fas fa-donate"></i> Recebimentos & Despesas</span>
  </a>
  <div class="dropdown-menu dropdown-menu-left" style="background: white;">
  <a style="" class="dropdown-item" href="{{ url('/recebimentos-operadoras') }}"> Recebimentos Operadoras</a>
  <a style="" class="dropdown-item" href="#"> Recebimentos Antecipados </a>
  <a style="" class="dropdown-item" href="#"> Despesas Extras (DOC/TEC/Aluguel/Outras/Tarifas)</a>
  <a style="" class="dropdown-item" href="{{ route('recebimentos-futuros.index') }}"> Previsão de Recebimentos Futuros</a>
  <a style="" class="dropdown-item" href="{{ url('/antecipacao')}}"> Antecipação Trava Livre</a>


</div> -->
</a>
</li>

<li>
  <a id="itemMenu" class="submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ route('recebimentos-futuros.index') }}" role="button">
    <span class="ml-1 nav-user-name hidden-sm"><i class="far fa-calendar-alt"></i> Recebimentos Futuros </span>
  </a>
</li>
<li>
  <a id="itemMenu" class="submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ url('/justificativas') }}" role="button">
    <span class="ml-1 nav-user-name hidden-sm"><i class="far fa-flag"></i> Justificativas </span>
  </a>
</li>
<!--
<li>
<a id="itemMenu" class="nav-linkk dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
aria-haspopup="false" aria-expanded="false">
<span dropzone=""class="ml-1 nav-user-name hidden-sm">Cadastros <i class="mdi mdi-chevron-down"></i> </span>
</a>
<div class="dropdown-menu dropdown-menu-left" style="background: white;">
<a style="" class="dropdown-item" href="{{ url('/historico-bancario') }}"> Histórico Bancário</a>
<a style="" class="dropdown-item" href="{{ url('/justificativas') }}"> Justificativas</a>

</div>
</li> -->
<!-- @if(Auth::user()->USUARIO_GLOBAL == 'S')
<li>
<a id="itemMenu" class="nav-linkk dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
aria-haspopup="false" aria-expanded="false">
<span class="ml-1 nav-user-name hidden-sm">Administrativo <i class="mdi mdi-chevron-down"></i> </span>
</a>
<div class="dropdown-menu dropdown-menu-left" style="background: white;">
<a style="" class="dropdown-item" href="{{ url('/autorizacao-credenciadora') }}"> Autorização Cielo</a>
</div>
</li>
@endif -->

<!-- <li>
<a id="itemMenu" class="nav-linkk dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
aria-haspopup="false" aria-expanded="false">
<span dropzone=""class="ml-1 nav-user-name hidden-sm">Projetos <i class="mdi mdi-chevron-down"></i> </span>
</a>
<div class="dropdown-menu dropdown-menu-left" style="background: white;">
<a style="" class="dropdown-item" href="/projetos"> Projetos</a>
</div> -->
</li>

</ul>

</nav>

</div>

<div class="modal fade" id="chamado" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header fundo-modal">
        <h5 class="modal-title" id="staticBackdropLabel">Abertura de chamado</h5>
      </div>
      <div class="alert alert-success" role="alert">
        <strong><i class="fas fa-check-circle"></i> Chamado aberto com sucesso! Em breve entraremos em contato.</strong>
      </div>
      <div class="modal-body tamanho-modal">
        <div class="row">
          <div class="col-sm-12">
            <h6> Departamento: </h6>

          </div>
          <div class="col-sm-12">
            <select id="departamento_chamado" onchange="listarCategorias({{Session::get('departamento_chamado')}}, {{Session::get(categoria_chamado)}})" class="form-control" name="departamento">
              @foreach( Session::get('departamento_chamado') as $departamento)
              <option value="{{ $departamento->EMAIL_DEPARTAMENTO }}">{{ $departamento->DEPARTAMENTO_CHAMADO}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-sm-12">
            <h6> Categoria: </h6>
          </div>
          <div class="col-sm-12">
            <select id="categoria_chamado" class="form-control" name="categoria">
              @foreach(Session::get('categoria_chamado') as $categoria)
              <!-- @if($categoria->COD_DEPARTAMENTO == 2) -->
              <option value="{{ $categoria->CATEGORIA_CHAMADO}}">{{ $categoria->CATEGORIA_CHAMADO}}</option>
              <!-- @endif -->
              @endforeach
            </select>
          </div>
          <div class="col-sm-12">
            <h6> Mensagem: </h6>
          </div>
          <div class="col-sm-12">
            <textarea class="form-control" name="mensagem"> </textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
        <button id="enviar_email" type="button" class="btn btn-success"><b>Confirmar</b></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="troca_cliente" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="trocacliente" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header fundo-modal">
        <h5 class="modal-title" id="trocacliente">Trocar cliente</h5>
      </div>
      <div class="modal-body tamanho-modal">
        <div class="row">
          <div class="col-sm-12">
            <h6> Clientes: </h6>
          </div>
          <div class="col-sm-12">
            <select id="troca_cliente" class="form-control" name="empresaescolhida">
              @foreach(Session::get('clientes') as $cliente)
              <option value="{{ $cliente->CODIGO }}">{{ $cliente->NOME}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
        <button id="trocar" type="button" class="btn btn-success"><b>Trocar</b></button>
      </div>
    </div>
  </div>
</div>

<script>

document.querySelector('.alert-success').style.display = 'none';

document.getElementById("trocar").addEventListener('click', function(e){
  const cliente = document.querySelector("select[name='empresaescolhida']").value;
  const usuario = document.querySelector("input[name='usuario']").value;

  console.log(cliente);
  //
  fetch('troca-empresa', {
    method: 'POST',
    // redirect: 'manual',
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    }),
    body: JSON.stringify({empresaescolhida: cliente, usuario_global: usuario }),
  })
  .then(function(response) {
    response.json().then(function(data) {
      if(data === 200){ location.href = "/" }
    })
  })
})

document.getElementById("enviar_email").addEventListener('click', function(){
  const departamento = document.querySelector('#departamento_chamado').value;
  const categoria = document.querySelector('#categoria_chamado').value;
  const mensagem = document.querySelector('textarea[name="mensagem"]').value;

  const email = {
    departamento,
    categoria,
    mensagem
  }

  $.ajax({
    url: "{{ url('enviar-email')}}",
    type: 'GET',
    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: email,
    success: function(response){
      document.querySelector('.alert-success').style.display = 'block';

      setTimeout(function() {
        $('#chamado').modal('hide');
        document.querySelector('.alert-success').style.display = 'none';
        document.querySelector('textarea[name="mensagem"]').value = "";
      }, 2500);
    },
    error: function(response){
      alert("Algo deu errado!");
    }
  })
});

function listarCategorias(departamentos, categorias){
  $("#categoria_chamado").empty();

  let select = document.getElementById('departamento_chamado');
  let value = select.options[select.selectedIndex].value;

  departamentos.forEach((departamento) => {
    if (departamento.EMAIL_DEPARTAMENTO == value) {
      categorias.forEach((categoria) => {
        if (categoria.COD_DEPARTAMENTO == departamento.CODIGO) {
          console.log(categoria.COD_DEPARTAMENTO)
          let option = new Option(categoria.CATEGORIA_CHAMADO, categoria.CATEGORIA_CHAMADO);
          let select = document.getElementById("categoria_chamado");
          select.add(option);
        }
      });
    }
  });
}

function checkNotification(){
  document.getElementById("notification").style.display = "none";
}

</script>
