@extends('layouts.analytics-master')

@section('title', 'Metrica - Admin & Dashboard Template')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  var success = "<?php echo session('success') ?>";

  if(success){
    $("#exampleModal").modal({
      show: true
    });
  }

  $(window).on('load', function(){
    $('#preloader').fadeOut('slow');
  });
});

</script>

@stop

@section('content')

<div id="preloader" style="display: none" class="loader"></div>

<div id="tudo_page" class="container-fluid">
  <div class="row">
    <div class="col-sm-12">

      @component('common-components.breadcrumb')
      @slot('title') Antecipação de Vendas @endslot
      @slot('item1') Antecipação @endslot
      <!-- @slot('item2') Antecipação de Venda @endslot -->
      @endcomponent

    </div><!--end col-->
  </div>
  <!-- end page title end breadcrumb -->
  <form id="myform" action="{{ action('AntecipacaoController@filtro')}}" method="post">
    <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title mt-0">Pesquisar:</h4>
            <div class="row">
              <div class="col-sm-8">
                <select id="op_filtro" name="tipo_filtro" onchange="mostrarOpFiltro()" class="custom-select" required>
                  <option selected value=""> Selecione um filtro </option>
                  <option value="1"> Por um período </option>
                  <option value="2"> Por um valor pretendido </option>
                  <!-- <option value="3"> Selecionar dados da lista </option> -->
                </select>
              </div>
            </div>
            <div id="filtrodata" style="display:none">

              <div style="margin-top: 30px" class="form-group">
                <div class="row">
                  <div class="col-sm-4">
                    <h6 style="color: #424242"> Data Inicial: </h6>
                    <input class="form-control" type="date" id="date_inicial" name="date_inicial" value="<?php echo date("Y-m-d"); ?>">
                  </div>
                  <div class="col-sm-4">
                    <h6 style="color: #424242"> Data Final: </h6>
                    <input class="form-control" type="date" id="date_final" name="date_final" value="<?php echo date("Y-m-d"); ?>">
                  </div>
                </div>
              </div>
              <div id="btfiltrodata" class="col-sm-4" style="padding: 0px; margin-top: -30px">
                <a style="margin-top: 30px; color: white" onclick="checkDate()" class="btn btn-secondary btn-round waves-effect waves-light"> <b>FILTRAR</b> <i class="fas fa-search"></i> </a>
              </div>
            </div>

            <div id="filtrovalor" style="display:none">
              <div style="margin-top: 30px;" class="form-group">
                <div class="row">
                  <div class="col-sm-8">
                    <h6 style="color: #424242"> Valor que deseja antecipar: </h6>
                    <input class="form-control" onKeyPress="return(moeda(this,'.',',',event))" placeholder="R$ 0,00" type="text" name="val_antecipacao">
                  </div>
                </div>
              </div>
            </div>
            <div id="btfiltro" class="col-sm-4" style="display:none; padding: 0px; margin-top: -30px">
              <a style="margin-top: 30px; color: white" onclick="submit()" class="btn btn-secondary btn-round waves-effect waves-light"> <b>FILTRAR</b> <i class="fas fa-search"></i> </a>
            </div>
          </form>


          <div class="card" style="margin-top: 22px;">
            <div class="card-header text-white mt-0" style="height:50px;background: #2D5275">
              <div style="margin-top:-5px; font-size: 20px; color: #FAFAFA">
                Informações da Antecipação
              </div>
            </div>
            <div class="card-body">
              <div class="col-sm-12">
                <div class="row">
                  <div class="col-6">
                    <h6 style="color: #424242"
                    class="card-title">
                    @if($valor_liquido)
                    Valor total selecionado: R$ <?php echo number_format($valor_liquido,2,",","."); ?>
                    @else
                    Valor total selecionado: R$ 0,00
                    @endif
                  </h6>
                  <h6 id="totalSelecionados"
                  style="color: #424242"
                  class="card-title">Total de recebíveis selecionados:
                  @if($count)
                  {{ $count }}
                  @else
                  0
                  @endif
                </h6>
              </div>
              <div class="col-6">
                <h6 style="color: red" class="card-title">Taxa antecipação: 2,00 %</h6>
                <h6 style="color: green" class="card-title">
                  @if($val_para_receber)
                  Valor líquido a receber: R$ <?php echo number_format($val_para_receber,2,",","."); ?>
                  @else
                  Valor líquido a receber:  R$ 0,00
                  @endif
                </h6>
              </div>
            </div>
            <div class="row">
              <a type="button" class="btn" onclick="checkSimulation()" style="background: #04B431; color: white">Solicitar Antecipação</a>
            </div>
          </div>
        </div>
      </div>
      <br>

      @if($result)
      <form name="formulario">
        <div style="font-size:15px">
          <table class="table">
            <thead class="thead-dark">
              <tr align="center">
                <th scope="col">EMPRESA</th>
                <th scope="col">ADQUIRENTE</th>
                <th scope="col">NSU</th>
                <th scope="col">VALOR BRUTO</th>
                <th scope="col">TAXA</th>
                <th scope="col">VALOR LÍQUIDO</th>
                <th scope="col">DATA PREV. PAGAMENTO</th>
              </tr>
            </thead>
            <tbody>
              @foreach($result as $results)
              <tr align="center">
                <td>{{ $results->EMPRESA}}</td>
                <td>{{ $results->ADQUIRENTE}}</td>
                <td>{{ $results->NSU}}</td>
                <td>{{ $results->VALOR_BRUTO}}</td>
                <?php $taxa = number_format($results->TAXA, 2, '.', ''); ?></td>
                <td>{{ $taxa }}
                  <?php $valor_liquido = number_format($results->VALOR_LIQUIUDO, 2, '.', ''); ?></td>
                  <td>{{ $valor_liquido}}</td>
                  <?php $newDate = date("d/m/Y", strtotime($results->DATA_PGTO));?>
                  <td>{{ $newDate }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </form>
        <div style="font-size: 15px;">
          @if(isset($date_inicial))
          {!! $result->appends(['tipo_filtro' => $tipo_filtro, 'date_inicial' => $date_inicial, 'date_final' => $date_final])->links() !!}
          @else

          @endif


        </div>
        @endif
      </div><!--end card-body-->
    </div><!--end card-->
  </div><!--end col-->
</div><!--end row-->

<form name="myformTrava" id="myformTrava" action="{{ action('HistoricoAntecipacaoController@antecipation')}}" method="post">
  <input type ="hidden" name="_token" value="{{{ csrf_token()  }}}">
  <input type="hidden" name="valor_liquido" value="{{{ $valor_liquido }}}">
</form>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Solicitação de Antecipação de Recebíveis</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6> Sua solicitação está em análise, poderá acompanhar todo o processor em http://www.trava-livre.com.br </h6>
        <br>
        <h6> Nº Processo: {{ session()->get('id_processo') }}</h6>
        <br>
        <h6> Data solicitação: {{ date("d/m/Y")}}</h6>
        <br>
        <h6> A confirmação será enviada em até 4 horas via e-mail.
        </div>
        <div class="modal-footer">
          <button data-dismiss="modal" type="button" class="btn btn-danger">FECHAR</button>
        </div>
      </div>
    </div>
  </div>

</div>
<script>

function submit(){
  document.getElementById("preloader").style.display = "block";
  document.getElementById("preloader").style.opacity = 0.9;

  setTimeout(function () {
    document.getElementById("myform").submit();
  },1000)
}

function submitTrava(){
  document.getElementById("preloader").style.display = "block";
  document.getElementById("preloader").style.opacity = 0.9;

  setTimeout(function () {
    document.getElementById("myformTrava").submit();
  },900)
}

function checkDate(){
  var inicio = document.getElementById("date_inicial").value;
  var final = document.getElementById("date_final").value;

  if(inicio > final){
    alert("ERROR: Data final precisa ser maior que a data inicial!");
    return;
  }
  submit();
}

function checkSimulation(){
  var campo = document.getElementById('totalSelecionados').innerHTML;

  var teste = campo.split(":");
  if(teste[1] == 0){
    alert("Simule uma antecipação antes de solicitar");
    return;
  }else{



    document.getElementById("preloader").style.display = "block";
    document.getElementById("preloader").style.opacity = 0.9;

    setTimeout(function () {
      document.getElementById("myformTrava").submit();
    },900)
  }
}

function mostrarOpFiltro(){
  var option = document.getElementById("op_filtro").value;

  if(option == 1){
    document.getElementById("filtrodata").style.display ="block";
    document.getElementById("filtrovalor").style.display ="none";
    document.getElementById("btfiltro").style.display ="none";

  }else if(option == 2){
    document.getElementById("filtrodata").style.display ="none";
    document.getElementById("filtrovalor").style.display ="block";
    document.getElementById("btfiltro").style.display ="block";
  }

}

function moeda(a, e, r, t) {
  let n = ""
  , h = j = 0
  , u = tamanho2 = 0
  , l = ajd2 = ""
  , o = window.Event ? t.which : t.keyCode;
  if (13 == o || 8 == o)
  return !0;
  if (n = String.fromCharCode(o),
  -1 == "0123456789".indexOf(n))
  return !1;
  for (u = a.value.length,
    h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
    ;
    for (l = ""; h < u; h++)
    -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
    if (l += n,
      0 == (u = l.length) && (a.value = ""),
      1 == u && (a.value = "0" + r + "0" + l),
      2 == u && (a.value = "0" + r + l),
      u > 2) {
        for (ajd2 = "",
        j = 0,
        h = u - 3; h >= 0; h--)
        3 == j && (ajd2 += e,
          j = 0),
          ajd2 += l.charAt(h),
          j++;
          for (a.value = "",
          tamanho2 = ajd2.length,
          h = tamanho2 - 1; h >= 0; h--)
          a.value += ajd2.charAt(h);
          a.value += r + l.substr(u - 2, u)
        }
        return !1
      }

      </script>
      @stop
