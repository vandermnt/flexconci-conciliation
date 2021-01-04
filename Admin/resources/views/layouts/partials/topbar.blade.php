
<!-- Top Bar Start -->
<div class="topbar">
  <!-- Navbar -->
  <nav class="navbar-custom">
    <ul class="list-unstyled topbar-nav float-right mb-0">
      <!-- <li class="hidden-sm">
      <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="javascript: void(0);" role="button"
      aria-haspopup="false" aria-expanded="false">
      English <img src="{{ URL::asset('assets/images/flags/us_flag.jpg')}}" class="ml-2" height="16" alt=""/> <i class="mdi mdi-chevron-down"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
    <a class="dropdown-item" href="javascript: void(0);"><span> German </span><img src="{{ URL::asset('assets/images/flags/germany_flag.jpg')}}" alt="" class="ml-2 float-right" height="14"/></a>
    <a class="dropdown-item" href="javascript: void(0);"><span> Italian </span><img src="{{ URL::asset('assets/images/flags/italy_flag.jpg')}}" alt="" class="ml-2 float-right" height="14"/></a>
    <a class="dropdown-item" href="javascript: void(0);"><span> French </span><img src="{{ URL::asset('assets/images/flags/french_flag.jpg')}}" alt="" class="ml-2 float-right" height="14"/></a>
    <a class="dropdown-item" href="javascript: void(0);"><span> Spanish </span><img src="{{ URL::asset('assets/images/flags/spain_flag.jpg')}}" alt="" class="ml-2 float-right" height="14"/></a>
    <a class="dropdown-item" href="javascript: void(0);"><span> Russian </span><img src="{{ URL::asset('assets/images/flags/russia_flag.jpg')}}" alt="" class="ml-2 float-right" height="14"/></a>
  </div>
</li> -->

<li class="dropdown notification-list"  style="color: white;  z-index:999">
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
</li>
<li class="dropdown">
  <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
  aria-haspopup="false" aria-expanded="false">
  <img src="{{ URL::asset('assets/images/users/user-4.jpg')}}" alt="profile-user" class="rounded-circle" />
  <!-- <span class="ml-1 nav-user-name hidden-sm">{{Session::get('codigologin') }} <i class="mdi mdi-chevron-down"></i> </span> -->
  <?php $primeiro_nome = explode(' ', Auth::user()->NOME); ?>
  <span class="ml-1 nav-user-name hidden-sm">{{$primeiro_nome[0] }} |   {{ Session::get('nome_fantasia')}} <i class="mdi mdi-chevron-down"></i> </span>

</a>
<div class="dropdown-menu dropdown-menu-right">
  <a class="dropdown-item" href="#"><i class="dripicons-user mr-2"></i> Editar Perfil</a>
  <a class="dropdown-item" href="#"><i class="dripicons-wallet mr-2"></i> Configurações</a>
  <a class="dropdown-item" href="#"><i class="dripicons-gear mr-2"></i> Ajuda</a>
  <!-- <a class="dropdown-item" href="#"><i class="dripicons-lock text-muted mr-2"></i> Sair</a> -->
  <div class="dropdown-divider"></div>
  <a class="dropdown-item" href="{{ url('/logout') }}"><i class="dripicons-exit mr-2"></i> Sair</a>
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

<div class="topbarr" style="margin-top: 70px;">
  <nav class="navbar-custom" style="background: white; min-height: 50px !important; border-bottom: 4px solid #2d5275">
    <ul class="list-unstyled topbar-nav mb-0" style="margin-left: 30px">
      <li>
        <a id="itemMenu" class="nav-linkk dropdown-toggle waves-effect waves-light nav-user"  data-toggle="dropdown" href="#" role="button"
        aria-haspopup="false" aria-expanded="false">
        <span class="ml-1 nav-user-name hidden-sm">Dashboard <i class="mdi mdi-chevron-down"></i> </span>
      </a>
      <div class="dropdown-menu dropdown-menu-left" style="background: white;">
        <a  class="dropdown-item" href="{{ url('/') }}"> Gerencial</a>
        <a  class="dropdown-item" href="#"> Diagnóstico Financeiro </a>
      </div>
    </li>

    <li>
      <a id="itemMenu" class="nav-linkk dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
      aria-haspopup="false" aria-expanded="false">
      <span class="ml-1 nav-user-name hidden-sm">Conciliação<i class="mdi mdi-chevron-down"></i> </span>
    </a>
    <div class="dropdown-menu dropdown-menu-left" style="background: white;">
      <a style="" class="dropdown-item" href="#"> Conferência Manual de Vendas</a>
      <a style="" class="dropdown-item" href="{{ url('/conciliacao-automatica') }}"> Conciliação Automática de Vendas </a>
      <a style="" class="dropdown-item" href="#"> Conciliação de Pagamentos </a>
      <a style="" class="dropdown-item" href="{{ url('/conciliacao-bancaria') }}"> Conciliação Bancária </a>
      <a style="" class="dropdown-item" href="#"> Conciliação de Taxas </a>
      <a style="" class="dropdown-item" href="#"> Conciliação de Aluguel e Outras Despesas </a>

    </div>
  </li>

  <li>
    <a id="itemMenu" class="nav-linkk dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
    aria-haspopup="false" aria-expanded="false">
    <span dropzone=""class="ml-1 nav-user-name hidden-sm">Vendas <i class="mdi mdi-chevron-down"></i> </span>
  </a>
  <div class="dropdown-menu dropdown-menu-left" style="background: white;">
    <a style="" class="dropdown-item" href="{{ url('/vendasoperadoras') }}"> Vendas Operadoras</a>
    <a style="" class="dropdown-item" href="{{ url('/vendas-sistema-erp')}}"> Vendas sistema de gestão (ERP) </a>
  </div>
</li>

<li>
  <a id="itemMenu" class="nav-linkk dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
  aria-haspopup="false" aria-expanded="false">
  <span class="ml-1 nav-user-name hidden-sm">Recebimentos <i class="mdi mdi-chevron-down"></i> </span>
</a>
<div class="dropdown-menu dropdown-menu-left" style="background: white;">
  <a style="" class="dropdown-item" href="{{ url('/recebimentos-operadora') }}"> Recebimentos Operadoras</a>
  <a style="" class="dropdown-item" href="#"> Recebimentos Antecipados </a>
  <a style="" class="dropdown-item" href="#"> Despesas Extras (DOC/TEC/Aluguel/Outras/Tarifas)</a>
  <a style="" class="dropdown-item" href="{{ url('/previsao-recebimentos') }}"> Previsão de Recebimentos Futuros</a>
  <a style="" class="dropdown-item" href="{{ url('/antecipacao')}}"> Antecipação Trava Livre</a>


</div>
</li>

<li>
  <a id="itemMenu" class="nav-linkk dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
  aria-haspopup="false" aria-expanded="false">
  <span dropzone=""class="ml-1 nav-user-name hidden-sm">Cadastros <i class="mdi mdi-chevron-down"></i> </span>
</a>
<div class="dropdown-menu dropdown-menu-left" style="background: white;">
  <a style="" class="dropdown-item" href="{{ url('/historico-bancario') }}"> Histórico Bancário</a>
  <a style="" class="dropdown-item" href="{{ url('/justificativas') }}"> Justificativas</a>

</div>
</li>
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

<script>

function checkNotification(){
  document.getElementById("notification").style.display = "none";
}

</script>
