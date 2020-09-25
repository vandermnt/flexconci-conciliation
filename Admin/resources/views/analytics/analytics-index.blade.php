@extends('layouts.analytics-master')

@section('title', 'Metrica - Admin & Dashboard Template')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>
<link href='lib/main.css' rel='stylesheet' />
<script src='lib/main.js'></script>


<script>

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var eventsList = [];
  var dados = <?php echo $dados_calendario ?>;
  var dados_pagamento = <?php echo $dados_calendario_pagamento ?>;

  dados_pagamento.forEach((teste) => {

    const total_liq = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(teste.val_liquido);

    eventsList.push(
      {
        title: total_liq,
        start: teste.DATA_PAGAMENTO,
        color: '#257e4a'

      },
      {
        title: 'Depositado',
        start: teste.DATA_PAGAMENTO,
        color: '#257e4a'
      }
    );
  });

  var eventos = eventsList;

  // eventos.forEach((events) => {

  dados.forEach((teste) => {
    var total_liq_prev_pagt = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(teste.val_liquido);

    // var hoy = new Date();
    // var fecha =  hoy.getFullYear() + '-' + ( hoy.getMonth() + 1 ) + '-' + hoy.getDate();

    var data_atual = "{{ $data }}";

    if(teste.DATA_PREVISTA_PAGTO >= data_atual){
console.log("dwakdpoakddopwakd");
      eventsList.push(
        {
          title: total_liq_prev_pagt,
          start: teste.DATA_PREVISTA_PAGTO,
          color: '#868A08'

        },
        {
          title: 'Previsão PGTO',
          start: teste.DATA_PREVISTA_PAGTO,
          color: '#868A08'
        }
      );
    }
  });

  // });




  var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
    },
    initialDate: '2020-09-12',
    navLinks: true, // can click day/week names to navigate views
    businessHours: true, // display business hours
    editable: true,
    selectable: true,
    events: eventsList
    //
    // dados.forEach((dados_calendario) => {
    //   {
    //     title: 'Depositado',
    //     start: dados_calendario.DATA_PREVISTA_PAGTO,
    //     url: 'http://google.com/',
    //     description: 'Lecture',
    //     color: '#257e4a'
    //   },
    //   {
    //     title: 'R$ 1.250,00',
    //     start: '2020-09-07',
    //     color: '#BDBDBD'
    //   },
    // });

  });

  calendar.render();
});

</script>
<style>

body {
  margin: 40px 10px;
  padding: 0;
  font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
  font-size: 14px;
}

#calendar {
  max-width: 1100px;
  margin: 0 auto;
}

</style>

@stop

@section('content')
<div class="container-fluid">
  <div class="row" style="align-items: center; justify-content: center;">
    <div class="col-lg-6">
      <div class="row" style="align-items: center; justify-content: center;">
        <div class="col-sm-2">
          <img src="{{ url('assets/images/user.png')}}"style="width: 120px;"/>
        </div>
        <div class="col-sm-9" style="padding: 0 25px">
          <?php $primeiro_nome = explode(' ', Auth::user()->NOME); ?>
          <h3> Bem vindo de volta, {{$primeiro_nome[0]}}! </h3>
          <h6 style="color: #6E6E6E"> Comece o seu dia analisando os dados da sua empresa.  </h6>
        </div>
      </div>

    </div> <!--end col-->
    <div class="col-lg-6" style="align-items: center; justify-content: center; text-align: center">
      <div class="row" style="padding: 30px">
        <div class="col-sm-4" >
          <img src="{{ url('assets/images/economia.svg')}}"style="width: 75px;"  data-toggle="tooltip" data-placement="bottom" title="Economia: R$ 377,00"/>
          <h6 style="font-size: 12px"> Economia: R$ 377,00 </h6>

        </div>
        <div class="col-sm-4">
          <!-- <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
          Tooltip on bottom
        </button> -->
        <img src="{{ url('assets/images/vendas.png')}}"style="width: 75px;" data-toggle="tooltip" data-placement="bottom" title="Vendas sem conciliar: 341"/>
        <h6 style="font-size: 12px"> Vendas s/ conciliar: 341 </h6>

      </div>
      <div class="col-sm-4">
        <!-- <h5> Vendas s/ conciliar: 341 </h5> -->
        <!-- <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
        Tooltip on bottom
      </button> -->
      <img src="{{ url('assets/images/banco.svg')}}"style="width: 75px;" data-toggle="tooltip" data-placement="bottom" title="Mensagem sobre extrato não enviado"/>
      <h6 style="font-size: 12px"> Extratos não enviados </h6>
    </div>
  </div>
</div> <!--end col-->
</div>


<div class="row">
  <div class="col-sm-12" style="margin-top: -30px">

    @component('common-components.breadcrumb')
    @slot('title') Home @endslot
    @slot('item1') Dashboard @endslot
    @endcomponent

  </div><!--end col-->
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <div class="row">
          @if(isset($dados_dash_vendas))
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h1 class="header-title mt-0" style="text-align: center">Dashboard Vendas</h1>
                <div class="row">
                  <div class="col-6">
                    <div class="dropdown">
                      <button class="btn btn-sm dropdown-toggle" style="background: #2D5275; color: white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item"  onclick="trocaPeriodo(1)">Ontem</a>
                        <a class="dropdown-item"  onclick="trocaPeriodo(2)">Últimos 7 dias</a>
                        <a class="dropdown-item"  onclick="trocaPeriodo(3)">Últimos 15 dias</a>
                        <a class="dropdown-item"  onclick="trocaPeriodo(4)">Últimos 30 dias</a>
                        <a class="dropdown-item"  onclick="trocaPeriodo(5)">Mês Atual</a>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="dropdown" style="text-align: right">
                      <button class="btn btn-sm dropdown-toggle pull-right" style="background: #2D5275; color: white; text-align: right" type="button" id="dropdownMenuButtonAgrupamento" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonAgrupamento">
                        <a class="dropdown-item" onclick="trocaAgrupamento(1)">Operadora</a>
                        <a class="dropdown-item" onclick="trocaAgrupamento(2)">Bandeira</a>
                        <a class="dropdown-item" onclick="trocaAgrupamento(3)">Modalidade</a>
                        <a class="dropdown-item" onclick="trocaAgrupamento(4)">Produto</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="ana_dash_1" class=""></div>
                <div class="table-responsive mt-4">
                  <table  id="table_vendas" class="table mb-0">
                    <thead>
                      <tr>
                        <th style="color: #231F20"  id="th_tipo">Tipo</th>
                        <th style="color: #231F20" >Quant.</th>
                        <th style="color: #231F20" >Val. Bruto</th>
                        <th style="color: #231F20" >Taxa</th>
                        <th style="color: #231F20" >Val. Líquido</th>
                        <th style="color: #231F20" >Tick. Médio</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- @foreach($dados_dash_vendas as $dados_vendas) -->
                      <tr>
                        <td style="color: #231F20"  id="tipo"> </td>
                        <td style="color: #231F20" id="quantidade"> </td>
                        <td style="color: #231F20" id="venda_total_bruto"></td>
                        <td style="color: #231F20" id="venda_total_taxa"></td>
                        <td style="color: #231F20" id="venda_total_liquido"></td>
                        <td style="color: #231F20" id="venda_ticket_medio"></td>
                      </tr>
                      <!-- @endforeach -->
                    </tbody>
                  </table>
                </div>
              </div><!--end card-body-->
            </div><!--end card-->
          </div><!--end col-->
          @endif

        </div><!--end row-->
      </div><!--end card-body-->
    </div><!--end card-->
  </div> <!--end col-->
  <div class="col-lg-6">
    <!-- <div class="col-lg-3" style="background: green; color: white; border-radius: 5px">
    <p> Período Visível: 7 dias </p>
  </div> -->
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h1 class="header-title mt-0" style="text-align: center">Dashboard Recebimentos Operadoras</h1>
              <div class="row">
                <div class="col-6">
                  <div class="dropdown">
                    <button class="btn btn-sm dropdown-toggle" style="background: #2D5275; color: white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Escolher Período <i class="mdi mdi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" onclick="">Ontem</a>
                      <a class="dropdown-item" onclick="">Últimos 7 dias</a>
                      <a class="dropdown-item" onclick="">Últimos 15 dias</a>
                      <a class="dropdown-item" onclick="">Últimos 30 dias</a>
                      <a class="dropdown-item" onclick="">Mês Atual</a>
                    </div>
                  </div>
                </div>
                <div class="col-6">

                  <div class="dropdown" style="text-align: right">
                    <button class="btn btn-sm dropdown-toggle pull-right" style="background: #2D5275; color: white; text-align: right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Escolhe Agrupamento <i class="mdi mdi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" onclick="">Operadora</a>
                      <a class="dropdown-item" onclick="">Bandeira</a>
                      <a class="dropdown-item" onclick="">Modalide</a>
                      <a class="dropdown-item" onclick="">Produto</a>
                    </div>
                  </div>
                </div>
              </div>
              <div id="ana_devicee" class=""></div>
              <div class="table-responsive mt-4">
                <table style="font-size: 12px" class="table mb-0">
                  <thead class="thead-light" style="width: 800px">
                    <tr>
                      <th>Valor Bruto</th>
                      <th>Taxa</th>
                      <th>Antecipação</th>
                      <th>Ajustes a débito</th>
                      <th>Ajustes a crédito</th>
                      <th>Valor Líquido</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <!-- <th style="background: white" scope="row">Dasktops</th> -->
                      <td>500</td>
                      <td>500</td>
                      <td>500</td>
                      <td>500</td>
                      <td>500</td>
                      <td>500</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div><!--end card-body-->
          </div><!--end card-->
        </div><!--end col-->

      </div><!--end row-->
    </div><!--end card-body-->
  </div><!--end card-->
</div> <!--end col-->

</div> <!--end row-->
<div class="row justify-content-center">

</div><!--end row-->

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <div id='loading'></div>

        <div id='calendar'></div>
      </div><!--end card-body-->
    </div><!--end card-->
  </div><!--end col-->

</div><!--end row-->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background: #2d5275;">
        <h5 class="modal-title" id="exampleModalLabel" style="color: white">Autorização Cielo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Autorização e Credenciamento EDI feito com sucesso!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModalErro" tabindex="-1" aria-labelledby="exampleModalLabelErro" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background: #2d5275;">
        <h5 class="modal-title" id="exampleModalLabelErro" style="color: white">ERRO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Não foi possivel realizar seu Credenciamento EDI, tente novamente!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
@stop

@section('footerScript')
<script src="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-us-aea-en.js') }}"></script>
<script type="text/javascript">
var param = <?php echo $dados_cliente ?>;
var dados_dash_vendas = <?php echo $dados_dash_vendas ?>;
var dados_dash_vendas_modalidade = <?php echo $dados_dash_vendas_modalidade ?>;
var dados_dash_vendas_bandeira = <?php echo $dados_dash_vendas_bandeira ?>;
</script>
<!-- <script type="text/javascript" src="assets/js/autorizacao-cielo.js">  </script> -->
<script type="text/javascript" src="assets/js/grafico-dash-vendas.js">  </script>

<script>

$(window).on("load", function () {
  preCarregarGraficoVendas();
  // página totalmente carregada (DOM, imagens etc.)
});


var periodo = null;
var grafico_vendas = null;
var iteration = 11

function getRandom() {
  var i = iteration;
  return (Math.sin(i / trigoStrength) * (i / trigoStrength) + i / trigoStrength + 1) * (trigoStrength * 2)
}

function getRangeRandom(yrange) {
  return Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min
}

$('#usa').vectorMap({
  map: 'us_aea_en',
  backgroundColor: 'transparent',
  borderColor: '#818181',
  regionStyle: {
    initial: {
      fill: '#506ee424',
    }
  },
  series: {
    regions: [{
      values: {
        "US-VA": '#506ee452',
        "US-PA": '#506ee452',
        "US-TN": '#506ee452',
        "US-WY": '#506ee452',
        "US-WA": '#506ee452',
        "US-TX": '#506ee452',
      },
      attribute: 'fill',
    }]
  },
});

function preCarregarGraficoVendas(){
  dados_grafico = [];

  dash_vendas = <?php echo $dados_dash_vendas ?>;
  dash_vendas.forEach((dados_dash) => {
    if(dados_dash.COD_PERIODO == 2){

      bruto = dados_dash.TOTAL_BRUTO;
      liquido = dados_dash.TOTAL_LIQUIDO;
      taxa = dados_dash.TOTAL_TAXA;
      ticket = dados_dash.TICKET_MEDIO;

      dados_grafico_total_bruto = parseFloat(bruto).toFixed(2);
      dados_grafico_total_liquido = parseFloat(liquido).toFixed(2);
      dados_grafico_total_taxa = parseFloat(taxa).toFixed(2);
      dados_grafico_total_ticket = parseFloat(ticket).toFixed(2);

      const total_bruto = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(dados_dash.TOTAL_BRUTO);
      const total_liquido = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(dados_dash.TOTAL_LIQUIDO);
      const total_taxa = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(dados_dash.TOTAL_TAXA);
      const total_ticket_medio = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(dados_dash.TICKET_MEDIO);

      dados_grafico.push(dados_dash);

      var html = "<tr>";

      html += "<td style='color: #231F20'>"+dados_dash.ADQUIRENTE+"</td>";
      html += "<td style='color: #231F20'>"+dados_dash.QUANTIDADE+"</td>";
      html += "<td style='color: #231F20'>"+total_bruto+"</td>";
      html += "<td style='color: #231F20'>"+total_taxa+"</td>";
      html += "<td style='color: #231F20'>"+total_liquido+"</td>";
      html += "<td style='color: #231F20'>"+total_ticket_medio+"</td>";

      html += "</tr>";

      $('#table_vendas').append(html);

      // document.getElementById("venda_total_bruto").innerHTML = total_bruto;
      // document.getElementById("venda_total_taxa").innerHTML = total_taxa;
      // document.getElementById("venda_total_liquido").innerHTML = total_liquido ;
      // document.getElementById("venda_ticket_medio").innerHTML = total_ticket_medio;
      // document.getElementById("quantidade").innerHTML = dados_dash.QUANTIDADE;
      // document.getElementById("tipo").innerHTML = "Operadora";

      document.getElementById("dropdownMenuButton").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
      document.getElementById("dropdownMenuButtonAgrupamento").innerHTML = "Operadora" + ' ' + '<i class="mdi mdi-chevron-down"></i>';
    }
  })
  // dropdownMenuButton
  periodo = 2;

  geraGraficoVendas(dados_grafico, 1);
}

function trocaPeriodo(cod_periodo){
  dados_grafico = [];

  if(cod_periodo == 1){
    <?php session()->put('periodo', 1); ?>
  }else if(cod_periodo == 2){
    <?php session()->put('periodo', 2); ?>
  }else if(cod_periodo == 3){
    <?php session()->put('periodo', 3); ?>
  }else if(cod_periodo == 4){
    <?php session()->put('periodo', 4); ?>
  }

  grupo = document.getElementById("th_tipo").innerHTML;

  if(grupo == "Operadora"){
    dash_vendas = <?php echo $dados_dash_vendas ?>;

    var total_bruto = 0;
    var total_liquido = 0;
    var total_taxa = 0;
    var total_ticket_medio = 0;
    var qtde = 0;

    dash_vendas.forEach((dados_dash) => {
      if(dados_dash.COD_PERIODO == cod_periodo){

        bruto = dados_dash.TOTAL_BRUTO;
        liquido = dados_dash.TOTAL_LIQUIDO;
        taxa = dados_dash.TOTAL_TAXA;
        ticket = dados_dash.TICKET_MEDIO;

        total_bruto = parseInt(total_bruto) + parseInt(dados_dash.TOTAL_BRUTO);
        total_liquido = parseInt(total_liquido) + parseInt(dados_dash.TOTAL_LIQUIDO);
        total_taxa = parseInt(total_taxa) + parseInt(dados_dash.TOTAL_TAXA);
        total_ticket_medio = parseInt(total_ticket_medio) + parseInt(dados_dash.TICKET_MEDIO);
        qtde = qtde + parseInt(dados_dash.QUANTIDADE);

        dados_grafico.push(dados_dash);

        document.getElementById("dropdownMenuButton").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
      }
    })

    if(dados_grafico.length == 0){
      console.log("VAZIOOOOOOOOOOOO");
    }else{
      grafico_vendas.destroy();

      document.getElementById("venda_total_bruto").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto);
      document.getElementById("venda_total_taxa").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa);
      document.getElementById("venda_total_liquido").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido);
      document.getElementById("venda_ticket_medio").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio);
      document.getElementById("quantidade").innerHTML = qtde;

      periodo = cod_periodo;
      geraGraficoVendas(dados_grafico, 1);
    }

  }else if(grupo == "Bandeira"){
    dash_vendas = <?php echo $dados_dash_vendas_bandeira ?>;
    //
    // var total_bruto = 0;
    // var total_liquido = 0;
    // var total_taxa = 0;
    // var total_ticket_medio = 0;
    // var qtde = 0;
    $('#table_vendas tbody').empty();

    dash_vendas.forEach((dados_dash) => {

      if(dados_dash.COD_PERIODO == cod_periodo){
        console.log("TESTEEEEEEE");
        // bruto = dados_dash.TOTAL_BRUTO;
        // liquido = dados_dash.TOTAL_LIQUIDO;
        // taxa = dados_dash.TOTAL_TAXA;
        // ticket = dados_dash.TICKET_MEDIO;
        //
        total_bruto = parseInt(dados_dash.TOTAL_BRUTO);
        total_liquido = parseInt(dados_dash.TOTAL_LIQUIDO);
        total_taxa = parseInt(dados_dash.TOTAL_TAXA);
        total_ticket_medio = parseInt(dados_dash.TICKET_MEDIO);
        qtde = parseInt(dados_dash.QUANTIDADE);

        // document.getElementById("venda_total_bruto").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto);
        // document.getElementById("venda_total_taxa").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa);
        // document.getElementById("venda_total_liquido").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido);
        // document.getElementById("venda_ticket_medio").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio);


        var html = "<tr>";

        html += "<td>"+dados_dash.BANDEIRA+"</td>";
        html += "<td>"+dados_dash.QUANTIDADE+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio)+"</td>";

        html += "</tr>";

        $('#table_vendas').append(html);

        dados_grafico.push(dados_dash);

        document.getElementById("dropdownMenuButton").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
      }
    })

    if(dados_grafico.length == 0){
      console.log("VAZIOOOOOOOOOOOO");
    }else{
      grafico_vendas.destroy();
      //
      // document.getElementById("venda_total_bruto").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto);
      // document.getElementById("venda_total_taxa").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa);
      // document.getElementById("venda_total_liquido").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido);
      // document.getElementById("venda_ticket_medio").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio);
      // document.getElementById("quantidade").innerHTML = qtde;

      periodo = cod_periodo;
      geraGraficoVendas(dados_grafico, 2);
    }
  }else if(grupo == "Modalidade"){
    dash_vendas = <?php echo $dados_dash_vendas_modalidade ?>;

    var total_bruto = 0;
    var total_liquido = 0;
    var total_taxa = 0;
    var total_ticket_medio = 0;
    var qtde = 0;

    dash_vendas.forEach((dados_dash) => {
      console.log(dash_vendas);

      if(dados_dash.COD_PERIODO == cod_periodo){

        bruto = dados_dash.TOTAL_BRUTO;
        liquido = dados_dash.TOTAL_LIQUIDO;
        taxa = dados_dash.TOTAL_TAXA;
        ticket = dados_dash.TICKET_MEDIO;

        total_bruto = parseInt(total_bruto) + parseInt(dados_dash.TOTAL_BRUTO);
        total_liquido = parseInt(total_liquido) + parseInt(dados_dash.TOTAL_LIQUIDO);
        total_taxa = parseInt(total_taxa) + parseInt(dados_dash.TOTAL_TAXA);
        total_ticket_medio = parseInt(total_ticket_medio) + parseInt(dados_dash.TICKET_MEDIO);
        qtde = qtde + parseInt(dados_dash.QUANTIDADE);

        dados_grafico.push(dados_dash);

        document.getElementById("dropdownMenuButton").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
      }
    })

    if(dados_grafico.length == 0){
      console.log("VAZIOOOOOOOOOOOO");
    }else{
      grafico_vendas.destroy();

      document.getElementById("venda_total_bruto").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto);
      document.getElementById("venda_total_taxa").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa);
      document.getElementById("venda_total_liquido").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido);
      document.getElementById("venda_ticket_medio").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio);
      document.getElementById("quantidade").innerHTML = qtde;
      periodo = cod_periodo;
      geraGraficoVendas(dados_grafico, 3);
    }

  }else if(grupo = "Produto"){

  }
}


</script>
@stop
