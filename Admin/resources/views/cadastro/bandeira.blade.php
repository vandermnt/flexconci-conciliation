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
      @slot('title') Bandeiras @endslot
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
              <h6> Bandeira: </h6>
              <div class="row form-group">
                <div class="col-sm-6">
                  <input type="hidden" value="{{$bandeiras}}" name="bandeiras-carregados">
                  <input type="textarea" class="form-control" placeholder="Pequise a bandeira" name="bandeiras-pesquisados">
                </div>
                <div class="col-sm-2">
                  <button class="btn btn-sm bt-cadastro-bandeira form-button"> <i class="fas fa-plus"></i> <b>Nova bandeira</b>  </button>
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
          <h4 id="qtd-registros">Total de bandeiras ({{ $count_bandeiras }} registros)</h4>
          <img src="assets/images/widgets/arrow-down.svg" alt="Bandeiras">
        </div>
        <br>
        <div class="tabela">
          <table id="tabela-bandeiras" class="table">
            <thead>
              <tr style="background: #2D5275; ">
                <th> CÓDIGO </th>
                <th> BANDEIRA </th>
                <th> AÇÕES </th>
              </tr>
            </thead>
            <tbody id="conteudo_tabe">
              @foreach($bandeiras as $bandeira)
              <tr id="{{ $bandeira->CODIGO }}">
                <td> {{ $bandeira->CODIGO }}</td>
                <td> {{ $bandeira->BANDEIRA }}</td>
                <td class="excluir">
                  <a href="#" onclick="editarBandeira(event, {{ $bandeira }})"><i class='far fa-edit'></i></a>
                  <a href="#" onclick="excluirBandeira(event,{{ $bandeira->CODIGO }})"><i style="margin-left: 12px"class="far fa-trash-alt"></i></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalCadastroBandeira" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Cadastro Bandeira</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="alert alert-success success-save-bandeira" role="alert">
          <strong><i class="fas fa-check-circle"></i> Bandeira cadastrada com sucesso.</strong>
        </div>
        <div class="modal-body">
          <div class="col-sm-12 form">
            <h6> Bandeira: </h6>
            <div class="row form-group">
              <div class="col-sm-12">
                <input type="textarea" class="form-control" name="bandeira">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
          <button type="button" class="btn btn-success bt-salva-bandeira"><b>Cadastrar</b></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalExcluirBandeira" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Exclusão Bandeira</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4>Deseja excluir esse bandeira?</h4>
        </div>
        <div class="modal-footer">
          <button id="sim" type="button" class="btn btn-success" data-dismiss="modal">Sim</button>
          <button id="nao" type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalEditarBandeira" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Editar Bandeira</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="alert alert-success success-update-bandeira" role="alert">
          <strong><i class="fas fa-check-circle"></i> Bandeira alterada com sucesso.</strong>
        </div>
        <div class="modal-body">
          <div class="col-sm-12 form">
            <h6> Bandeira: </h6>
            <div class="row form-group">
              <div class="col-sm-12">
                <input type="textarea" class="form-control" name="editar-bandeira">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
          <button type="button" class="btn btn-success bt-salva-edicao-bandeira"><b>Salvar</b></button>
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
document.querySelector(".success-save-bandeira").style.display = "none";
document.querySelector(".success-update-bandeira").style.display = "none";

const buttonCadastroAdquirente = document.querySelector(".bt-cadastro-bandeira");
const buttonSalvaCadastro = document.querySelector(".bt-salva-bandeira");
const buttonSalvaEdicao = document.querySelector(".bt-salva-edicao-bandeira");
const buttonSimExclusao = document.querySelector("#sim");
const bandeirasPesquisados = document.querySelector('input[name="bandeiras-pesquisados"]');
const bandeirasCarregados = document.querySelector('input[name="bandeiras-carregados"]');

buttonSalvaCadastro.addEventListener("click", function() {
  const bandeira = document.querySelector('input[name="bandeira"]').value;
  cadastrarBanco(bandeira);
});

buttonSalvaEdicao.addEventListener("click", function() {
  const bandeira = document.querySelector('input[name="editar-bandeira"]').value;
  salvarEdicaoAdquirente(bandeira);
});

buttonCadastroAdquirente.addEventListener("click", function() {
  $("#modalCadastroBandeira").modal({
    show: true
  });
});

bandeirasPesquisados.addEventListener('keydown', function() {
  const bandeiras = JSON.parse(bandeirasCarregados.value);

  setTimeout(function(){
    if(bandeirasPesquisados.value == '') {
      for(bandeira of bandeiras) {
        document.getElementById(bandeira.CODIGO).style = "display: "
      }
    } else {
      for(bandeira of bandeiras) {

        var regex = new RegExp(bandeirasPesquisados.value, 'gi');
        resultado = bandeira.BANDEIRA.match(regex);

        if(resultado) {
          console.log(resultado);
          document.getElementById(bandeira.CODIGO).style = "display: ";
        }else{
          document.getElementById(bandeira.CODIGO).style.display = "none";
        }
      }
    }
  }, 300);
})

function editarBandeira(e, bandeira){
  localStorage.setItem("cod_bandeira", bandeira.CODIGO);

  $("#modalEditarBandeira").modal({
    show: true
  });
  document.querySelector("input[name='editar-bandeira']").value = `${bandeira.BANDEIRA}`;
}

function salvarEdicaoAdquirente(bandeira) {
  const headers = new Headers();
  const data = { _token: "{{csrf_token()}}", bandeira };

  fetch("update-bandeira/" + localStorage.getItem('cod_bandeira'), {
    method: "PUT",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    }),
    body: JSON.stringify(data)
  })
  .then(function(response) {
    response.json().then(function(data) {
      document.querySelector(".success-update-bandeira").style.display = "block";

      setTimeout(function() {
        $('#modalEditarBandeira').modal('hide');
        document.querySelector(".success-update-bandeira").style.display = "none";
        document.querySelector('input[name="editar-bandeira"]').value = "";
      }, 2000);

      atualizaTabela();
    });
  })
  .catch(error => alert("Algo deu errado!"));
}

function cadastrarBanco(bandeira) {
  const headers = new Headers();
  const data = { _token: "{{csrf_token()}}", bandeira };

  fetch("cadastro-bandeira", {
    method: "POST",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    }),
    body: JSON.stringify(data)
  })
  .then(function(response) {
    response.json().then(function(data) {
      document.querySelector(".success-save-bandeira").style.display = "block";

      setTimeout(function() {
        document.querySelector(".success-save-bandeira").style.display = "none";
        document.querySelector('input[name="bandeira"]').value = "";
      }, 2500);

      atualizaTabela();
    });
  })
  .catch(error => alert("Algo deu errado!"));
}

function atualizaTabela() {
  buscarTodasBandeiras();
}

function buscarTodasBandeiras() {
  fetch("load-bandeiras", {
    method: "GET",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      Accept: "application/json"
    })
  }).then(function(response) {
    response.json().then(function(data) {
      renderizaTabela(data.bandeiras);
    });
  });
}

function renderizaTabela(bandeiras) {
  document.querySelector("#conteudo_tabe").innerHTML = "";

  let html = "";

  for (bandeira of bandeiras) {
    html += `<tr id='${bandeira.CODIGO}'>
    <td> ${bandeira.CODIGO} </td>
    <td> ${bandeira.BANDEIRA} </td>
    <td>
    <a href='#' onclick='editarBandeira(event, ${JSON.stringify(bandeira)})'><i class='far fa-edit'></i></a>
    <a href='#' onclick='excluirBandeira(event, ${bandeira.CODIGO})'><i style='margin-left: 12px' class='far fa-trash-alt'></i></a>"
    </td>
    </tr>`;
  }

  document.querySelector(
    "#qtd-registros"
  ).innerHTML = `Total de bandeiras (${bandeiras.length} registros)`;
  document.querySelector("#conteudo_tabe").innerHTML = html;
}

function excluirBandeira(e, codigo_bandeira) {
  e.preventDefault();

  localStorage.setItem("cod_bandeira", codigo_bandeira);
  $("#modalExcluirBandeira").modal({
    show: true
  });
}

document.getElementById("sim").addEventListener("click", function() {
  const cod_bandeira = localStorage.getItem("cod_bandeira");

  fetch(`delete-bandeira/${cod_bandeira}`, {
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    })
  }).then(function(response) {
    response.json().then(function(data) {
      buscarTodasBandeiras();
    });
  });
});

</script>

@stop

@stop
