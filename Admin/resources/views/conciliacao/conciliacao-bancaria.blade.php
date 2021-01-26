@extends('layouts.analytics-master')

@section('title', 'Metrica - Admin & Dashboard Template')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>

@stop

@section('content')

<div id="tudo_page" class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      @component('common-components.breadcrumb')
      @slot('title') Conciliação Bancária @endslot
      @slot('item1') Conciliação @endslot
      @endcomponent
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <!-- <div class="row">
          <form enctype="multipart/form-data" method="post">
          <div class="col-lg-12">
          <div class="form-group">
          <label for="exampleInputPassword1">Upload Extratos Bancários</label>
          <div class="input-group">
          <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
          <div class="custom-file">
          <input type="file" name="extratos[]" class="custom-file-input" id="files" multiple>
          <label class="custom-file-label" for="inputGroupFile04">Selecione os arquivos</label>
        </div>
        <div class="input-group-append">
        <button class="btn btn" id="submitExtratos" onclick="enviarExtratos()" type="button">Enviar</button>
      </div>
    </div><br>

    <label id="resultado"></label>
  </div>
</div>
</form>
</div> -->
<form>
  <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
  <div class="row">
    <div class="col-xl-12" style="padding: 10px 400px">
      <div class="card">
        <div class="">
          <h5 class="mt-0 ">Faça o upload dos extratos aqui:</h5>
          <!-- <p class="text-muted mb-3"></p> -->
          <input style="width: 50%" type="file" name="extratos[]" id="input-file-now" multiple class="dropify" />
          <button type="button" class="btn btn-lg btn-block" id="btEnviarExtrato" onclick="enviarExtratos()" ><b>ENVIAR EXTRATOS </b></button>
        </div><!--end card-body-->
      </div><!--end card-->
    </div><!--end col-->
  </div><!--end row-->
</form>

<h5> Conciliações em processamento </h5>
<div style="overflow: scroll; font-size: 13px; max-height: 230px">

  <table id="table_processamento" class="table " style="white-space: nowrap; background:white; color: #2D5275">

    <thead>
      <tr style="background: #2D5275; ">
        <th style="color: white"> Data de Envio  </th>
        <th style="color: white"> Hora de Envio   </th>
        <th style="color: white"> Status </th>
        <th style="color: white"> Histórico  </th>
      </tr>
    </thead>
    <tbody>
      <tr id="">

      </tr>
    </tbody>

  </table>
</div>
<br>
<h5> Histórico </h5>

<div id="filtrodata">
  <div class="form-group">
    <div class="row">

      <div class="col-sm-3">
        <h6 style="color: #424242; font-size: 11.5px"> Data Inicial: </h6>
        <input style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" type="date" id="date_inicial" value="{{  date("Y-m-01")}}" name="data_inicial">
      </div>
      <div class="col-sm-3">
        <h6 style="color: #424242; font-size: 11.5px"> Data Final: </h6>
        <input style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" type="date" id="date_final" value="{{ date("Y-m-d") }}" name="data_final">
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6" >
        <div id="filtroempresa">
          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <h6 style="color: #424242; font-size: 11.5px"> Operadora: </h6>
                <input id="adquirente" style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" name="adquirente">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-2">
        <button id="buttonpesquisar" type="button" class="btn btn-sm" data-toggle="modal" data-target="#staticBackdropBandeira" style="margin-top: 9px; width: 120%; margin-top: 25px">
          <b> Selecione Operadora</b>
        </button>
      </div>

      <div class="col-sm-6" style="margin-top: -16px">
        <div id="filtroempresa">
          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <h6 style="color: #424242; font-size: 11.5px"> Domicílio Bancário: </h6>
                <input id="bandeira" style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" name="bandeira">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-2">
        <button id="buttonpesquisar" type="button" class="btn btn-sm" data-toggle="modal" data-target="#staticBackdropBandeira" style="margin-top: 9px; width: 120%">
          <b> Selecione Domicílio Bancário</b>
        </button>
      </div>

    </div>

    <div class="row">
      <div class="col-sm-12" style="text-align: right">
        <a id="" onclick="limparFiltros()" style="align-items: right; background: white; color: #2D5275; border-color: #2D5275" class="btn btn-sm"> <i class="far fa-trash-alt"></i> <b>Limpar Campos</b>  </a>

        <a id="submitFormLogin" style="align-items: right; background: white; color: #2D5275; border-color: #2D5275" class="btn btn-sm"> <i class="fas fa-search"></i> <b>Pesquisar</b>  </a>

      </div>
    </div>


  </div>

</div>
<div style="overflow: scroll; font-size: 13px; max-height: 230px">

  <table id="jsgrid-table" class="table " style="white-space: nowrap; background:white; color: #2D5275">

    <thead>
      <tr style="background: #2D5275; ">
        <th style="color: white"  class=''> Banco  </th>
        <th style="color: white"  class=''> Conta   </th>
        <th style="color: white"  class=''> Operadora </th>
        <th style="color: white"  class=''> Data Recebimento  </th>
        <th style="color: white"  class=''> Bruto  </th>
        <th style="color: white"  class=''> Descontos </th>
        <th style="color: white"  class=""> Líquido Previsto </th>
        <th style="color: white"  class=""> Depositado </th>
        <th style="color: white"  class=""> Diferença </th>
        <th style="color: white"  class=""> Status </th>

      </tr>
    </thead>
    <tbody>
      @foreach($result as $results)
      <tr id="{{$results->COD}}" onclick="mudaCorLinhaTable({{$results->COD}})">
        <td><?php echo ucfirst(strtolower($results->EMPRESA)) ?></td>
        <?php $newDate = date("d/m/Y", strtotime($results->DATA_VENDA));?>
        <td>{{$results->CNPJ}}</td>
        <td>{{$results->ADQUIRENTE}}</td>
        <td>{{$newDate}}</td>
        <?php $newDatePrev= date("d/m/Y", strtotime($results->DATA_PREVISTA_PAGTO));?>
        <td>{{$newDatePrev}}</td>
        <td>{{$results->BANDEIRA}}</td>
        <td>{{$results->DESCRICAO}}</td>
        <td>{{$results->NSU}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

</div>
</div>
</div>
</div>
</div><!--end card-body-->

@section('footerScript')
<script src="{{ URL::asset('assets/pages/jquery.form-upload.init.js')}}"></script>
<script src="{{ URL::asset('plugins/dropify/js/dropify.min.js')}}"></script>
<script>

// document.getElementById('files').addEventListener('change', handleFileSelect, false);

function handleFileSelect() {
  var div = document.getElementById("resultado");

  arquivos = $("#files").prop("files");
  var nomes = $.map(arquivos, function(val) { return val.name; });
  for(x=0;x<nomes.length;x++){
    var extensao = nomes[x].split('.').pop().toLowerCase();
    var nome = nomes[x].substring(nomes[x].lastIndexOf("/"),nomes[x].length);
    div.innerHTML = div.innerHTML + nome + ", ";
  }
}

function enviarExtratos(){
  var extratos = document.getElementsByName("extratos");

  var form = $('form')[0];
  var formData = new FormData(form);

  $.ajax({
    url: "{{ url('enviar-extrato') }}",
    type: "post",
    header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: formData,
    processData: false,
    contentType: false,
    success: function (response){
      alert("Extratos enviados com sucesso!");

      $.ajax({
        url: "{{ url('atualizar-conciliacoes-processadas') }}",
        type: "get",
        header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        processData: false,
        contentType: false,
        success: function (response){
          for(var i=0; i<response.length; i++){

            var data_envio = new Date(response[i].DATA_ENVIO);
            var data_envio_formatada = data_envio.toLocaleDateString();

            var html = "<tr>"
            html += "<td>"+data_envio_formatada+"</td>";
            html += "<td>"+response[i].HORA_ENVIO+"</td>";
            html += "<td>"+"Processando"+"</td>";
            html += "<td>"+response[i].HISTORICO+"</td>";

            html += "</tr>";
            $("#table_processamento").append(html);
          }
        }
      })
    }
  })
}

</script>
@stop
@stop
