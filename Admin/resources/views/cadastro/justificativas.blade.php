@extends('layouts.analytics-master')

@section('title', 'Metrica - Admin & Dashboard Template')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>

<link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

@stop

@section('content')

<div id="preloader" style="display: none" class="loader"></div>

<div id="tudo_page" class="container-fluid">
  <div class="row">
    <div class="col-sm-12">

      @component('common-components.breadcrumb')
      @slot('title') Justificativas @endslot
      @slot('item1') Cadastro @endslot
      <!-- @slot('item2') Antecipação de Venda @endslot -->
      @endcomponent

    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body" >
          <div class="row" style="margin-top: -16px">
            <div class="col-sm-12">
              <div id="filtroempresa">
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-8">
                      <h6 style="color: #424242; font-size: 11.5px"> Justificativa: </h6>
                      <input id="justificativa" type="textarea" style="padding-left: 7px; padding-top: 5px; padding-bottom: ; border-color: #2D5275" class="form-control" name="justificativa">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <div id="btfiltro" style="margin-top: -4px; display:block; text-align: right">
                <a href="" onclick="limparFiltros()" style="align-items: right; background: white; color: #2D5275; border-color: #2D5275" class="btn btn-sm"> <i class="far fa-trash-alt"></i> <b>Limpar Campos</b>  </a>
                <button type="button"  onclick="postCadastroJustificativa()" style="align-items: right; background: white; color: #2D5275; border-color: #2D5275" class="btn btn-sm"> <i class="fas fa-save"></i> <b>Salvar</b>  </button>
              </div>
            </div>
          </div>


        </div>

        <div style="overflow: auto">
          <table id="table_historico_bancario" class="table " style="white-space: nowrap;">

            <thead>
              <tr style="background: #2D5275; ">
                <th> Data de Cadastro  </th>
                <th> Justificativa </th>
                <th> Ação </th>

              </tr>
            </thead>
            <tbody id="conteudo_tabe">
              @foreach($justificativas as $justificativa)
              <tr id="{{ $justificativa->CODIGO }}">
                <td id="{{ "datacad_".$justificativa->CODIGO}}"> <?php echo date("d/m/Y", strtotime($justificativa->DATA_CADASTRO));?> </td>
                <td id="{{ "just".$justificativa->CODIGO}}"> {{ $justificativa->JUSTIFICATIVA }}</td>
                <td class="excluir"> <a href="#" onclick="excluirJustificativa({{$justificativa->CODIGO}})"><i style="margin-left: 12px"class="far fa-trash-alt"></i></a></td>
              </tr>
              @endforeach
            </tbody>

          </table>
        </div>

      </div><!--end card-body-->
    </div><!--end card-->
  </div><!--end col-->

  <div class="modal" id="modalCadastroJustificativa" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Cadastro Justificativa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Justificativa cadastrada com sucesso!</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalExcluirJustificativa" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Excluir Justificativa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Deseja excluir esse justificativa?</p>
        </div>
        <div class="modal-footer">
          <button id="sim" type="button" class="btn btn-success" data-dismiss="modal">Sim</button>
          <button id="nao" type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
        </div>
      </div>
    </div>
  </div>

  <!-- <div class="modal" id="modalEditarHistorico" tabindex="-1">
  <div class="modal-dialog">
  <div class="modal-content">
  <div class="modal-header" style="background: #2D5275">
  <h5 class="modal-title" style="color: white">Editar Histórico Bancário</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<h5>Dados Histórico Bancário</h5>
<input id="date" type="date" class="form-control" value="" disabled>

<h6 style="color: #424242; font-size: 11.5px; margin-top: 12px"> Adquirentes: </h6>
<select class="custom-select" id="modal_adquirente" name="adquirentes" style="border-color: #2D5275; margin-top: -7px" required>
<option selected disabled>Selecione o Adquirente</option>
@foreach($adquirentes as $adquirente)
<option value="{{ $adquirente->CODIGO."*".$adquirente->ADQUIRENTE }}"> {{ $adquirente->ADQUIRENTE }}</option>
@endforeach
</select>

<h6 style="color: #424242; font-size: 11.5px; margin-top: 12px"> Banco: </h6>
<select class="custom-select" id="modal_banco" name="bancos" style="border-color: #2D5275; margin-top: -7px" required>
<option selected disabled value="">Selecione o Banco</option>
@foreach($bancos as $banco)
<option value="{{ $banco->CODIGO."*".$banco->BANCO }}"> {{ $banco->BANCO }}</option>
@endforeach
</select>

<h6 style="color: #424242; font-size: 11.5px; margin-top: 12px"> Histórico Bancário: </h6>
<input id="modal_histbanco" value="" class="form-control" style="border-color: #2D5275; margin-top: -7px" name="">

</div>
<div class="modal-footer">
<button id="sim" onclick="salvarEdicaoHistorico()" type="button" class="btn btn-success" data-dismiss="modal">Salvar</button>
<button id="nao" type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
</div>
</div>
</div>
</div> -->
</div>

</div>
@section('footerScript')
<!-- Required datatable js -->
<script src="{{ URL::asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Buttons examples -->
<script src="{{ URL::asset('plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/jszip.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/pdfmake.min.js')}}"></script>
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

function limparFiltros(){
  document.getElementById("justificativa").value = "";
}

function postCadastroJustificativa(){

  var justificativa = document.getElementById("justificativa").value;

  $.ajax({
    url: "{{ url('post-justificativa') }}",
    type: "post",
    header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: ({_token: '{{csrf_token()}}', justificativa}),
    dataType: 'json',
    success: function(response){
      $("#modalCadastroJustificativa").modal({
        show: true
      });

      $.ajax({
        url: "{{ url('load-justificativas') }}",
        type: "get",
        header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(response){
          // todos_historicos = localStorage.setItem('todos_historicos', JSON.stringify(response));

          var node = document.getElementById("conteudo_tabe");

          $("#conteudo_tabe").empty();

          console.log(response);


          for(i=0; i<response.length; i++){


            html = "<tr id='"+response[i].CODIGO+"'>";
            html += "<td id='datacad_"+response[i].CODIGO+"'>"+response[i].DATA_CADASTRO+"</td>";
            html += "<td id='historico"+response[i].CODIGO+"'>"+response[i].JUSTIFICATIVA+"</td>";
            html += "<td class='excluir'>"+"<a href='#'  onclick='excluirJustificativa("+response[i].CODIGO+")''><i style='margin-left: 12px'class='far fa-trash-alt'></i></a>"+"</td>";

            html += "</td>";
            $("#table_historico_bancario").append(html);
          }
        }
      });

      document.getElementById("justificativa").innerHTML = "";
    }
  });
}

function excluirJustificativa(codigo_justificativa){
  localStorage.setItem('cod_justificativa', codigo_justificativa);

  $("#modalExcluirJustificativa").modal({
    show:true
  });
}

// function editarHistorico(cod_historico){
//
//   let todos_historicos = localStorage.getItem('todos_historicos');
//   // transformar em objeto novamente
//   let histObj = JSON.parse(todos_historicos);
//
//   var historico = histObj;
//
//   localStorage.setItem('cod_historico', cod_historico);
//
//   $("#modalEditarHistorico").modal({
//     show:true
//   });
//
//   historico.forEach((hist) => {
//     if(hist.CODIGO == cod_historico){
//
//       document.getElementById("date").value = hist.DATA_CADASTRO;
//       document.getElementById("modal_histbanco").value = hist.HISTORICO_BANCO;
//     }
//
//   })
// }

// function salvarEdicaoHistorico(){
//
//   let todos_historicos = localStorage.getItem('todos_historicos');
//   // transformar em objeto novamente
//   let histObj = JSON.parse(todos_historicos);
//
//   var historico = histObj;
//
//   var cod = localStorage.getItem('cod_historico');
//
//   historico.forEach((hist) => {
//     if(hist.CODIGO == cod){
//       adq = $('#modal_adquirente').val();
//       banco = $('#modal_banco').val();
//
//       adq_selecionado = adq.split("*");
//       banco_selecionado = banco.split("*");
//
//       console.log(adq);
//       console.log(banco);
//       //
//       document.getElementById("historico"+cod).innerHTML = document.getElementById("modal_histbanco").value;
//       document.getElementById("adq"+cod).innerHTML = adq_selecionado[1];
//       document.getElementById("banco"+cod).innerHTML = banco_selecionado[1];
//
//     }
//   })
// }

document.getElementById("sim").addEventListener('click', function() {

  var teste = localStorage.getItem('cod_justificativa');

  var url = "{{ url('delete-justificativa') }}" + "/" + teste;
  $.ajax({
    url: url,
    type: "get",
    header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: ({_token: '{{csrf_token()}}', teste}),
    dataType: 'json',
    success: function(response){

      id_linha = "#"+teste;

      $(id_linha).remove();

    }

  });

}, false);

document.getElementById("nao").addEventListener('click', function() {
  console.log("naoooooooo")
}, false);


</script>
@stop
