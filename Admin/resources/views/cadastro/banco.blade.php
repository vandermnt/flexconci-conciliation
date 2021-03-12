@extends('layouts.analytics-master')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>
<link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/cadastro/cadastros.css')}}" rel="stylesheet" type="text/css" />
@stop

@section('content')

<div id="tudo_page" class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      @component('common-components.breadcrumb')
      @slot('title') Bancos @endslot
      @slot('item1') Cadastro @endslot
      @endcomponent
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body" >
          <div class="form-group">
            <div class="col-sm-12 form">
              <h6> Banco: </h6>
              <div class="row form-group">
                <div class="col-sm-6">
                  <input type="hidden" value="{{$bancos}}" name="bancos-carregados">
                  <input type="textarea" class="form-control" placeholder="Pequise o banco" name="bancos-pesquisados">
                </div>
                <div class="col-sm-2">
                  <button class="btn btn-sm bt-cadastro-ad form-button"> <i class="fas fa-plus"></i> <b>Novo banco</b>  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-12">
            <div id="btfiltro" style="display:block; text-align: right;">
              <!-- <button style="align-items: right; background: white; color: #2D5275; border-color: #2D5275" class="btn btn-sm limpa-filtro"> <i class="far fa-trash-alt"></i> <b>Limpar Campos</b>  </button> -->
            </div>
          </div>
        </div>
        <div class="col-sm-12 table-description d-flex align-items-center ">
          <h4 id="qtd-registros">Total de bancos ({{ $count_bancos }} registros)</h4>
          <img src="assets/images/widgets/arrow-down.svg" alt="Bancos">
        </div>
        <br>
        <div class="tabela">
          <table id="tabela-bancos" class="table">
            <thead>
              <tr style="background: #2D5275; ">
                <th> CÓDIGO </th>
                <th> BANCO </th>
                <th> AÇÕES </th>
              </tr>
            </thead>
            <tbody id="conteudo_tabe">
              @foreach($bancos as $banco)
              <tr id="{{ $banco->CODIGO }}">
                <td> {{ $banco->CODIGO }}</td>
                <td> {{ $banco->BANCO }}</td>
                <td class="excluir">
                  <a href="#" onclick="editarBanco(event, {{ $banco }})"><i class='far fa-edit'></i></a>
                  <a href="#" onclick="excluirBanco(event,{{ $banco->CODIGO }})"><i style="margin-left: 12px"class="far fa-trash-alt"></i></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalCadastroBanco" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Cadastro Banco</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="alert alert-success success-save-banco" role="alert">
          <strong><i class="fas fa-check-circle"></i> Banco cadastrado com sucesso.</strong>
        </div>
        <div class="modal-body">
          <div class="col-sm-12 form">
            <h6> Banco: </h6>
            <div class="row form-group">
              <div class="col-sm-12">
                <input type="textarea" class="form-control" name="banco">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
          <button type="button" class="btn btn-success bt-salva-banco"><b>Cadastrar</b></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalExcluirBanco" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Exclusão Banco</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4>Deseja excluir esse banco?</h4>
        </div>
        <div class="modal-footer">
          <button id="sim" type="button" class="btn btn-success" data-dismiss="modal">Sim</button>
          <button id="nao" type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalEditarBanco" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Editar Banco</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="alert alert-success success-update-banco" role="alert">
          <strong><i class="fas fa-check-circle"></i> Banco alterado com sucesso.</strong>
        </div>
        <div class="modal-body">
          <div class="col-sm-12 form">
            <h6> Banco: </h6>
            <div class="row form-group">
              <div class="col-sm-12">
                <input type="textarea" class="form-control" name="editar-banco">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
          <button type="button" class="btn btn-success bt-salva-edicao-banco"><b>Salvar</b></button>
        </div>
      </div>
    </div>
  </div>
</div>
@section('footerScript')
<!-- Required datatable js -->
<script src="{{ URL::asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Buttons examples -->
<script src="{{ URL::asset('plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.colVis.min.js')}}"></script>
<!-- Responsive examples -->
<script src="{{ URL::asset('plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ URL::asset('assets/pages/jquery.datatable.init.js')}}"></script>

<script type="text/javascript">
document.querySelector(".success-save-banco").style.display = "none";
document.querySelector(".success-update-banco").style.display = "none";

const buttonCadastroAdquirente = document.querySelector(".bt-cadastro-ad");
const buttonSalvaCadastro = document.querySelector(".bt-salva-banco");
const buttonSalvaEdicao = document.querySelector(".bt-salva-edicao-banco");
const buttonSimExclusao = document.querySelector("#sim");
const bancosPesquisados = document.querySelector('input[name="bancos-pesquisados"]');
const bancosCarregados = document.querySelector('input[name="bancos-carregados"]');

buttonSalvaCadastro.addEventListener("click", function() {
  const banco = document.querySelector('input[name="banco"]').value;
  cadastrarBanco(banco);
});

buttonSalvaEdicao.addEventListener("click", function() {
  const banco = document.querySelector('input[name="editar-banco"]').value;
  salvarEdicaoAdquirente(banco);
});

buttonCadastroAdquirente.addEventListener("click", function() {
  $("#modalCadastroBanco").modal({
    show: true
  });
});

bancosPesquisados.addEventListener('keydown', function() {
  const bancos = JSON.parse(bancosCarregados.value);

  setTimeout(function(){
    if(bancosPesquisados.value == '') {
      for(banco of bancos) {
        document.getElementById(banco.CODIGO).style = "display: ";
      }
    } else {
      for(banco of bancos) {

        var regex = new RegExp(bancosPesquisados.value, 'gi');
        resultado = banco.BANCO.match(regex);

        if(resultado) {
          document.getElementById(banco.CODIGO).style = "display: ";
        }else{
          document.getElementById(banco.CODIGO).style.display = "none";
        }
      }
    }
  }, 300);
})

function editarBanco(e, banco){
  localStorage.setItem("cod_banco", banco.CODIGO);

  $("#modalEditarBanco").modal({
    show: true
  });
  document.querySelector("input[name='editar-banco']").value = `${banco.BANCO}`;
}

function salvarEdicaoAdquirente(banco) {
  const headers = new Headers();
  const data = { _token: "{{csrf_token()}}", banco };

  fetch("update-banco/" + localStorage.getItem('cod_banco'), {
    method: "PUT",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    }),
    body: JSON.stringify(data)
  })
  .then(function(response) {
    response.json().then(function(data) {
      document.querySelector(".success-update-banco").style.display = "block";

      setTimeout(function() {
        $('#modalEditarBanco').modal('hide');
        document.querySelector(".success-update-banco").style.display = "none";
        document.querySelector('input[name="editar-banco"]').value = "";
      }, 2000);

      atualizaTabela();
    });
  })
  .catch(error => alert("Algo deu errado!"));
}

function cadastrarBanco(banco) {
  const headers = new Headers();
  const data = { _token: "{{csrf_token()}}", banco };

  fetch("cadastro-banco", {
    method: "POST",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    }),
    body: JSON.stringify(data)
  })
  .then(function(response) {
    response.json().then(function(data) {
      document.querySelector(".success-save-banco").style.display = "block";

      setTimeout(function() {
        document.querySelector(".success-save-banco").style.display = "none";
        document.querySelector('input[name="banco"]').value = "";
      }, 2500);

      atualizaTabela();
    });
  })
  .catch(error => alert("Algo deu errado!"));
}

function atualizaTabela() {
  buscarTodosBancos();
}

function buscarTodosBancos() {
  fetch("load-bancos", {
    method: "GET",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      Accept: "application/json"
    })
  }).then(function(response) {
    response.json().then(function(data) {
      renderizaTabela(data.bancos);
      console.log(data.bancos);
    });
  });
}

function renderizaTabela(bancos) {
  document.querySelector("#conteudo_tabe").innerHTML = "";

  let html = "";

  for (banco of bancos) {
    html += `<tr id='${banco.CODIGO}'>
    <td> ${banco.CODIGO} </td>
    <td> ${banco.BANCO} </td>
    <td>
    <a href='#' onclick='editarBanco(event, ${JSON.stringify(banco)})'><i class='far fa-edit'></i></a>
    <a href='#' onclick='excluirBanco(event, ${banco.CODIGO})'><i style='margin-left: 12px' class='far fa-trash-alt'></i></a>"
    </td>
    </tr>`;
  }

  document.querySelector(
    "#qtd-registros"
  ).innerHTML = `Total de bancos (${bancos.length} registros)`;
  document.querySelector("#conteudo_tabe").innerHTML = html;
}

function excluirBanco(e, codigo_banco) {
  e.preventDefault();

  localStorage.setItem("cod_banco", codigo_banco);
  $("#modalExcluirBanco").modal({
    show: true
  });
}

document.getElementById("sim").addEventListener("click", function() {
  const cod_banco = localStorage.getItem("cod_banco");

  fetch(`delete-banco/${cod_banco}`, {
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    })
  }).then(function(response) {
    response.json().then(function(data) {
      buscarTodosBancos();
    });
  });
});

</script>

@stop

@stop
