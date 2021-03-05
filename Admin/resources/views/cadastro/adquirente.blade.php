@extends('layouts.analytics-master')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>
<link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" />

@stop

@section('content')

<div id="preloader" style="display: none" class="loader"></div>

<div id="tudo_page" class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      @component('common-components.breadcrumb')
      @slot('title') Adquirentes @endslot
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
              <h6> Adquirente: </h6>
              <div class="row form-group">
                <div class="col-sm-6">
                  <input type="textarea" class="form-control" placeholder="Pequise o adquirente" name="adquirentes-pesquisados">
                </div>
                <div class="col-sm-2">
                  <button class="btn btn-sm bt-cadastro-ad form-button"> <i class="fas fa-plus"></i> <b>Nova Adquirente</b>  </button>
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

        <div style="overflow: auto; padding: 0px 30px">
          <table id="tabela-adquirentes" class="table " style="white-space: nowrap;">
            <thead>
              <tr style="background: #2D5275; ">
                <th> CÓDIGO </th>
                <th> ADQUIRENTE </th>
                <th> HOMOLOGADO </th>
                <th> AÇÕES </th>
              </tr>
            </thead>
            <tbody id="conteudo_tabe">
              @foreach($adquirentes as $adquirente)
              <tr id="{{ $adquirente->CODIGO }}">
                <td> {{ $adquirente->CODIGO }}</td>
                <td> {{ $adquirente->ADQUIRENTE }}</td>
                <td> @if($adquirente->HOMOLOGADO)
                  <i style="color: green" class="fas fa-check"></i>
                  @else
                  <i  style="color: red" class="fas fa-times"></i>
                  @endif
                </td>
                <td class="excluir">
                  <a href="#" onclick="editarAdquirente({{$adquirente->CODIGO}})"><i class='far fa-edit'></i></a>
                  <a href="#" onclick="excluirAdquirente(event,{{ $adquirente->CODIGO}})"><i style="margin-left: 12px"class="far fa-trash-alt"></i></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalCadastroAdquirente" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Cadastro Adquirente</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="alert alert-success success-save-ad" role="alert">
          <strong><i class="fas fa-check-circle"></i> Adquirente cadastrado com sucesso.</strong>
        </div>
        <div class="modal-body">
          <div class="col-sm-12 form">
            <h6> Adquirente: </h6>
            <div class="row form-group">
              <div class="col-sm-12">
                <input type="textarea" class="form-control" name="adquirente">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
          <button type="button" class="btn btn-success bt-salva-ad"><b>Cadastrar</b></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalExcluirAdquirente" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Cadastro Adquirente</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4>Deseja excluir esse adquirente?</h4>
        </div>
        <div class="modal-footer">
          <button id="sim" type="button" class="btn btn-success" data-dismiss="modal">Sim</button>
          <button id="nao" type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalExcluirAdquirente" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Excluir Adquirente</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4>Deseja excluir esse adquirente?</h4>
        </div>
        <div class="modal-footer">
          <button id="sim" type="button" class="btn btn-success" data-dismiss="modal">Sim</button>
          <button id="nao" type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
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
@stop

<script>
document.querySelector(".success-save-ad").style.display = "none";
const buttonLimparFiltro = document.querySelector(".limpa-filtro");
const buttonCadastroAdquirente = document.querySelector(".bt-cadastro-ad");
const buttonSalvaCadastro = document.querySelector(".bt-salva-ad");
const buttonSimExclusao = document.querySelector("#sim");

buttonCadastroAdquirente.addEventListener("click", function(){
  $("#modalCadastroAdquirente").modal({
    show: true
  });
})

buttonSalvaCadastro.addEventListener("click", function(){
  const adquirente = document.querySelector('input[name="adquirente"]').value;
  cadastrarAdquirente(adquirente);
})

buttonLimparFiltro.addEventListener("click", function(){
  document.getElementById("adquirente").value = "";
})

buttonSimExclusao.addEventListener("click", function(){
  console.log("VOu excluir!!!");
})

function cadastrarAdquirente(adquirente) {
  const headers = new Headers();
  const data = ({_token: '{{csrf_token()}}', adquirente});

  fetch("cadastro-adquirente", {
    method: 'POST',
    headers: new Headers({
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }),
    body: JSON.stringify(data),
  })
  .then(function(response){
    response.json().then(function(data){
      document.querySelector(".success-save-ad").style.display = "block";

      setTimeout(function() {
        document.querySelector('.success-save-ad').style.display = 'none';
        document.querySelector('input[name="adquirente"]').value = "";
      }, 2500);

      renderizaNovoAdquirente(data);
    });
  })
  .catch(error => alert("Algo deu errado!"))
}

function renderizaNovoAdquirente(adquirente) {
  // $("#modalCadastroAdquirente").modal({
  //   show: true
  // });
}

function postCadastroJustificativa(){
  let justificativa = document.getElementById("justificativa").value;

  $.ajax({
    url: "{{ url('post-justificativa') }}",
    type: "post",
    header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: ({_token: '{{csrf_token()}}', justificativa}),

    success: function(response){
      $("#modalCadastroJustificativa").modal({
        show: true
      });

      $.ajax({
        url: "{{ url('load-justificativas') }}",
        type: "get",
        header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(response){
          $("#conteudo_tabe").empty();

          let html = "";

          for(let i=0; i < response.length; i++){
            html = "<tr id='" + response[i].CODIGO + "'>";
            html += "<td id='historico" + response[i].CODIGO + "'>" + response[i].JUSTIFICATIVA + "</td>";
            html += "<td class='excluir'> " +
            "<a href='#' onclick='editarJustificativa("+response[i].CODIGO+")'><i class='far fa-edit'></i></a> " +
            "<a href='#' onclick='excluirJustificativa("+response[i].CODIGO+")''><i style='margin-left: 12px' class='far fa-trash-alt'></i></a>" +
            "</td>";
            html += "</tr>";

            $("#table_justificativa").append(html);
          }
        }
      });

      $('#justificativa').val("");
    }
  });
}

function excluirAdquirente(e, codigo_adquirente){
  e.preventDefault();

  localStorage.setItem('cod_adquirente', codigo_adquirente);
  $("#modalExcluirAdquirente").modal({
    show:true
  });
}

function editarJustificativa(cod_justificativa){
  let url = "{{ url('justificativa') }}" + "/" + cod_justificativa;

  $.ajax({
    url: url,
    type: "get",
    header:{
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response){
      $('#justificativaEdit').val(response.JUSTIFICATIVA);

      $("#modalEditarJustificativa").modal({
        show:true
      });
    }
  });

  localStorage.setItem('cod_justificativa', cod_justificativa);
}

function salvarEdicaoJustificativa(){
  let justificativa = {
    codigo: localStorage.getItem('cod_justificativa'),
    justificativa: $('#justificativaEdit').val()
  };

  $.ajax({
    type: "PUT",
    url: 'api/justificativa/' + localStorage.getItem('cod_justificativa'),
    data: justificativa,
    context: this,
    header:{
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (data) {
      let linhas = $('#' + localStorage.getItem("cod_justificativa"));

      linhas[0].cells[0].textContent = justificativa.justificativa.toUpperCase();
    }
  })
}

document.getElementById("sim").addEventListener('click', function() {

  const cod_adquirente = localStorage.getItem('cod_adquirente');
  // let url = "{{ url('delete-justificativa') }}" + "/" + teste;
  console.log("CODIGO :" + cod_adquirente)

  fetch(`delete-adquirente/${cod_adquirente}`,{
    headers: new Headers({
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }),
  })
  .then(function(response) {
    response.json().then(function(data){
      console.log(data);
    })
  })

  // $.ajax({
  //   url: url,
  //   type: "get",
  //   header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
  //   data: ({_token: '{{csrf_token()}}', teste}),
  //   dataType: 'json',
  //   success: function(response){
  //
  //     id_linha = "#"+teste;
  //
  //     $(id_linha).remove();
  //
  //   }
  //
  // });

}, false);


</script>
@stop
