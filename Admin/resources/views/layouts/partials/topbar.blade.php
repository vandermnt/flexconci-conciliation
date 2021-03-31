
<!-- Top Bar Start -->
<div class="topbar">
  <!-- Navbar -->
  <nav class="navbar-custom">
    <ul class="list-unstyled topbar-nav float-right mb-0">
      @if(Auth::user()->USUARIO_GLOBAL === 'S')
      <li class="dropdown" onmouseover="showSubmenu(this);" onmouseout="hiddeSubmenu(this);">
        <a id="dropdownCadastros" class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
        aria-haspopup="false" aria-expanded="false">
        <span class="ml-1 nav-user-name hidden-sm"> Administrativo <i class="mdi mdi-chevron-down"></i> </span>
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownCadastros">
        <a class="dropdown-item" href="#"> Conciliação Automática </a>
        <a class="dropdown-item" href="#"> Desconciliação Automática </a>
      </div>
    </li>

    <li class="dropdown" onmouseover="showSubmenu(this);" onmouseout="hiddeSubmenu(this);">
      <a id="dropdownCadastros" class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
      aria-haspopup="false" aria-expanded="false">
      <span class="ml-1 nav-user-name hidden-sm"> Cadastros <i class="mdi mdi-chevron-down"></i> </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownCadastros">
      <a class="dropdown-item" href="{{ url('cadastro-adquirente')}}"> Operadoras </a>
      <a class="dropdown-item" href="{{ url('cadastro-banco')}}"> Bancos </a>
      <a class="dropdown-item" href="{{ url('cadastro-bandeira')}}"> Bandeiras </a>
      <a class="dropdown-item" href="{{ url('cadastro-taxa')}}"> Taxas </a>
			<a class="dropdown-item" href="{{ url('/justificativas') }}"> Justificativas </a>
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
<li class="dropdown" onmouseover="showSubmenu(this);" onmouseout="hiddeSubmenu(this);">
  <a id="dropdownUserSettings" class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
  aria-haspopup="false" aria-expanded="false">
  <?php $primeiro_nome = explode(' ', Auth::user()->NOME); ?>
  <span class="ml-1 nav-user-name hidden-sm">{{$primeiro_nome[0] }} |   {{ Session::get('nome_fantasia')}} <i class="mdi mdi-chevron-down"></i> </span>
  <input type="hidden" name="usuario" value="{{ Auth::user()->CODIGO }}">
</a>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownUserSettings">
  <a class="dropdown-item" href="{{ url('/logout') }}"><i class="dripicons-exit mr-2"></i> Sair</a>
</div>
</li>
</ul>

<ul class="topbar-icons list-unstyled topbar-nav mb-0">
<li style="margin-left: 30px; margin-top: -3px; ">
  <a class="nav-link" href="{{ url('/')  }}">
    <img src="{{ URL::asset('assets/images/logconcibr.png')}}" style="width: 130px;" alt="">
  </a>
</li>
</ul>
</nav>
<!-- end navbar-->
</div>

<div class="topbarr submenu" style="margin-top: 70px;">
  <nav class="navbar-custom" style="background: white; min-height: 50px !important; border-bottom: 2px solid #2d5275">
    <ul class="list-unstyled topbar-nav mb-0" style="margin-left: 30px">
      <li>
        <a id="itemMenu" class="submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user"  href="{{ url('/') }}" role="button">
          <span class="nav-user-name hidden-sm"><i class="fas fa-chart-bar mr-1"></i> Dashboard </span>
        </a>
    </li>

		<li>
			<div class="dropdown navbar-submenu" onmouseover="showSubmenu(this);" onmouseout="hiddeSubmenu(this);">
				<button id="itemMenu" class="btn submenu-item nav-linkk dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="far fa-credit-card mr-1"></i>
					Vendas
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a id="itemMenu" class="submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ url('/vendas-sistema-erp')}}" role="button">
						<span dropzone=""class="nav-user-name hidden-sm"><i class="fas fa-laptop mr-1"></i>Vendas {{session('erp_cliente') ?? 'ERP'}}</span>
					</a>
					<a id="itemMenu" class="submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ url('/vendas-operadoras') }}" role="button">
						<span dropzone=""class="nav-user-name hidden-sm"><i class="fas fa-money-check-alt mr-1"></i> Vendas Operadoras </span>
					</a>
				</div>
			</div>
		</li>

		<li>
			<div class="dropdown navbar-submenu" onmouseover="showSubmenu(this);" onmouseout="hiddeSubmenu(this);">
				<button id="itemMenu" class="btn submenu-item nav-linkk dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-check mr-1"></i>
					Conciliação
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a id="itemMenu" class="dropdown-item submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ route('conciliacao-vendas') }}" role="button"
					aria-haspopup="false" aria-expanded="false">
					<span class="nav-user-name hidden-sm"><i class="far fa-handshake mr-1"></i> Conciliação de Vendas </span>
					</a>
					<a id="itemMenu" class="dropdown-item submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ route('conciliacao-taxas') }}" role="button"
					aria-haspopup="false" aria-expanded="false">
					<span class="nav-user-name hidden-sm"><i class="fas fa-percent mr-1"></i> Conciliação de Taxas </span>
					</a>
					<a id="itemMenu" class="dropdown-item submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ route('conciliacao-bancaria') }}" role="button"
					aria-haspopup="false" aria-expanded="false">
					<span class="nav-user-name hidden-sm"><i class="fas fa-coins mr-1"></i>Conciliação Bancária </span>
					</a>
				</div>
			</div>
		</li>

		<li>
			<div class="dropdown navbar-submenu" onmouseover="showSubmenu(this);" onmouseout="hiddeSubmenu(this);">
				<button id="itemMenu" class="btn submenu-item nav-linkk dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-hand-holding-usd mr-1"></i>
					Recebimentos
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a id="itemMenu" class="submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ url('/recebimentos-operadoras') }}" role="button">
						<span class="nav-user-name hidden-sm"><i class="fas fa-donate mr-1"></i> Recebimentos & Despesas</span>
					</a>
					<a id="itemMenu" class="submenu-item nav-linkk dropdown-toggle waves-effect waves-light nav-user" href="{{ route('recebimentos-futuros.index') }}" role="button">
						<span class="nav-user-name hidden-sm"><i class="far fa-calendar-alt mr-1"></i> Recebimentos Futuros </span>
					</a>
				</div>
			</div>
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
