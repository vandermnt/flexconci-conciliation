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
        description: teste.CODIGO,
        start: teste.DATA_PAGAMENTO,
        color: '#257e4a',
        background: '#FF4000',
        publicId: teste.DATA_PAGAMENTO

      },
      // {
      //   title: 'Depositado',
      //   start: teste.DATA_PAGAMENTO,
      //   color: '#257e4a'
      // }
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
      eventsList.push(
        {
          title: total_liq_prev_pagt,
          start: teste.DATA_PREVISTA_PAGTO,
          color: '#2D93AD'
        },
        // {
        //   title: 'Previsão',
        //   start: teste.DATA_PREVISTA_PAGTO,
        //   color: '#2D93AD'
        // }
      );
    }


  });

  // });




  var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      // right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      right: ''

    },
    // initialDate: '2020-09-12',
    height:550,
    navLinks: false, // can click day/week names to navigate views
    businessHours: true, // display business hours
    // editable: true,
    // selectable: true,

    events: eventsList,
    eventClick: function(calEvent, jsEvent, view) {
      if(calEvent.event._def.extendedProps.publicId){
        document.getElementById("preloader").style.display = "block";
        showRecebiveis(calEvent.event._def.extendedProps.publicId, calEvent.event._def.title);

      }
    }

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
  $(".fc-prev-button").append('<i class="glyphicon"...</i>')

  calendar.render();
});

</script>

<script type="text/javascript">
$(document).ready(function(){

  $(window).on('load', function(){
    $('#preloader').fadeOut('slow');
  });
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
  <div class="row" style="margin-bottom: 20px">
    <div class="col-lg-6">
      <div class="row" style="align-items: center; justify-content: center;">
        <div class="col-sm-3">
          <img src="{{ url('assets/images/user.png')}}"style="width: 120px; "/>
        </div>
        <div class="col-sm-9">
          <?php $primeiro_nome = explode(' ', Auth::user()->NOME); ?>
          <h3> Bem vindo de volta, {{$primeiro_nome[0]}}! </h3>
          @if($frase)
          <h6 style="color: #6E6E6E"> {{$frase->AVISO_GERAL}}  </h6>

          @else
          <h6 style="color: #6E6E6E"> Comece o seu dia analisando os dados da sua empresa.  </h6>

          @endif
        </div>
      </div>

    </div>
    <!-- <div class="col-lg-6" style="align-items: center; justify-content: center; text-align: center">
    <div class="row" style="padding: 30px">
    <div class="col-sm-4" >
    <img src="{{ url('assets/images/economia.svg')}}"style="width: 75px;"  data-toggle="tooltip" data-placement="bottom" title="Economia: R$ 377,00"/>
    <h6 style="font-size: 12px"> Economia: R$ 377,00 </h6>

  </div>
  <div class="col-sm-4">
  <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
  Tooltip on bottom
</button>
<img src="{{ url('assets/images/vendas.png')}}"style="width: 75px;" data-toggle="tooltip" data-placement="bottom" title="Vendas sem conciliar: 341"/>
<h6 style="font-size: 12px"> Vendas s/ conciliar: 341 </h6>

</div>
<div class="col-sm-4">
<h5> Vendas s/ conciliar: 341 </h5>
<button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
Tooltip on bottom
</button>
<img src="{{ url('assets/images/banco.svg')}}"style="width: 75px;" data-toggle="tooltip" data-placement="bottom" title="Mensagem sobre extrato não enviado"/>
<h6 style="font-size: 12px"> Extratos não enviados </h6>
</div>
</div>
</div>  -->
</div>


<div class="row">
  <div class="col-sm-12" style="margin-top: -30px">

    @component('common-components.breadcrumb')
    @slot('title') Gerencial @endslot
    @slot('item1') Dashboard @endslot
    @endcomponent

  </div><!--end col-->
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="card" style="height: 570px">
      <div class="card-body">
        <div class="row">
          @if(isset($dados_dash_vendas))
          <div class="col-lg-12">
            <h4 class="mt-0" style="text-align: center">Vendas por Operadora</h4>
            <div class="row">
              <div class="col-6">
                <div class="dropdown">
                  <button class="btn btn-sm dropdown-toggle" style="background: #2D5275; color: white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item"  onclick="trocaPeriodo(1, 'operadora')">Ontem</a>
                    <a class="dropdown-item"  onclick="trocaPeriodo(2, 'operadora')">Últimos 7 dias</a>
                    <a class="dropdown-item"  onclick="trocaPeriodo(3, 'operadora')">Últimos 15 dias</a>
                    <a class="dropdown-item"  onclick="trocaPeriodo(4, 'operadora')">Últimos 30 dias</a>
                    <a class="dropdown-item"  onclick="trocaPeriodo(5, 'operadora')">Mês Atual</a>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="dropdown" style="text-align: right">
                  <a class="dropdown-toggle pull-right" onclick="gerarPdfVendasOperadora()" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ url('/assets/images/export.png')}}" style="width: 45px" alt="">
                  </a>
                  <!-- <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonAgrupamento">
                  <a class="dropdown-item" oncli        <link href="{{ URL::asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
                  ck="trocaAgrupamento(1)">Operadora</a>
                  <a class="dropdown-item" onclick="trocaAgrupamento(2)">Bandeira</a>
                  <a class="dropdown-item" onc        <link href="{{ URL::asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
                  lick="trocaAgrupamento(3)">Modalidade</a>
                  <a class="dropdown-item" onclick="trocaAgrupamento(4)">Produto</a>
                </div> -->
              </div>
            </div>
          </div>
          <div id="apex_pie2" class="apex-charts"></div>
          <div class="table-responsive mt-4" style="border-color: red">
            <table  id="table_vendas_operadora" class="table table-borderless tableDadosDash" style="font-size: 12px; max-height: 150px">
              <thead>
                <tr>
                  <th style="color: #231F20" >Operadora</th>
                  <th style="color: #231F20" >Qtd.</th>
                  <th style="color: #231F20" >Bruto</th>
                  <th style="color: #231F20" >Taxa</th>
                  <th style="color: #231F20" >Líquido</th>
                  <th style="color: #231F20" >Ticket Médio</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
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
<div class="card" style="height: 570px">
  <div class="card-body">
    <div class="row">
      <div class="col-lg-12">
        <h4 class="mt-0" style="text-align: center">Vendas por Bandeira</h4>
        <div class="row">
          <div class="col-6">
            <div class="dropdown">
              <button class="btn btn-sm dropdown-toggle" style="background: #2D5275; color: white" type="button" id="dropdownMenuButtonBandeira" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Escolher Período <i class="mdi mdi-chevron-down"></i>
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" onclick="trocaPeriodo(1, 'bandeira')">Ontem</a>
                <a class="dropdown-item" onclick="trocaPeriodo(2, 'bandeira')">Últimos 7 dias</a>
                <a class="dropdown-item" onclick="trocaPeriodo(3, 'bandeira')">Últimos 15 dias</a>
                <a class="dropdown-item" onclick="trocaPeriodo(4, 'bandeira')">Últimos 30 dias</a>
                <a class="dropdown-item" onclick="trocaPeriodo(5, 'bandeira')">Mês Atual</a>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="dropdown" style="text-align: right">
              <a class="dropdown-toggle pull-right" onclick="gerarPdfVendasBandeira()" type="button" id="dropdownMenuButtonAgrupamento" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{ url('/assets/images/export.png')}}" style="width: 45px" alt="">
              </a>
              <!-- <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonAgrupamento">
              <a class="dropdown-item" onclick="trocaAgrupamento(1)">Operadora</a>
              <a class="dropdown-item" onclick="trocaAgrupamento(2)">Bandeira</a>
              <a class="dropdown-item" onclick="trocaAgrupamento(3)">Modalidade</a>
              <a class="dropdown-item" onclick="trocaAgrupamento(4)">Produto</a>
            </div> -->
          </div>
        </div>
      </div>
      <div id="apex_pie7" class="apex-charts"></div>
      <div class="table-responsive mt-4" style="font-size: 13px; overflow-y: auto; max-height: 150px">

        <table id="table_vendas_bandeira"  class="table table-borderless tableDadosDash" style="font-size: 12px">

          <thead>
            <tr>
              <th style="color: #231F20" >Bandeira</th>
              <th style="color: #231F20" >Qtd.</th>
              <th style="color: #231F20" >Bruto</th>
              <th style="color: #231F20" >Taxa</th>
              <th style="color: #231F20" >Líquido</th>
              <th style="color: #231F20" >Ticket Médio</th>
            </tr>
          </thead>
          <tbody style="">
          </tbody>
        </table>
      </div>

    </div><!--end row-->
  </div><!--end card-body-->
</div><!--end card-->
</div> <!--end col-->
</div>

</div> <!--end row-->

<div class="row">
  <div class="col-lg-6">
    <div class="card" style="max-height: 610px">
      <div class="card-body">
        <div class="row">
          @if(isset($dados_dash_vendas))
          <div class="col-lg-12">
            <h4 class="mt-0" style="text-align: center">Vendas por Forma de Pagamento</h4>
            <div class="row">
              <div class="col-6">
                <div class="dropdown">
                  <button class="btn btn-sm dropdown-toggle" style="background: #2D5275; color: white" type="button" id="dropdownMenuButtonModalidade" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonModalidade">
                    <a class="dropdown-item"  onclick="trocaPeriodo(1, 'modalidade')">Ontem</a>
                    <a class="dropdown-item"  onclick="trocaPeriodo(2, 'modalidade')">Últimos 7 dias</a>
                    <a class="dropdown-item"  onclick="trocaPeriodo(3, 'modalidade')">Últimos 15 dias</a>
                    <a class="dropdown-item"  onclick="trocaPeriodo(4, 'modalidade')">Últimos 30 dias</a>
                    <a class="dropdown-item"  onclick="trocaPeriodo(5, 'modalidade')">Mês Atual</a>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="dropdown" style="text-align: right">
                  <a class="dropdown-toggle pull-right" onclick="gerarPdfVendasModalidade()" type="button" id="dropdownMenuButtonAgrupamento" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ url('/assets/images/export.png')}}" style="width: 45px" alt="">
                  </a>
                  <!-- <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonAgrupamento">
                  <a class="dropdown-item" onclick="trocaAgrupamento(1)">Operadora</a>
                  <a class="dropdown-item" onclick="trocaAgrupamento(2)">Bandeira</a>
                  <a class="dropdown-item" onclick="trocaAgrupamento(3)">Modalidade</a>
                  <a class="dropdown-item" onclick="trocaAgrupamento(4)">Produto</a>
                </div> -->
              </div>
            </div>
          </div>
          <div id="apex_pie8" class="apex-charts"></div>
          <div class="table-responsive mt-4" style="font-size: 13px; overflow-y: auto; max-height: 160px">

            <table id="table_vendas_modalidade"  class="table table-borderless tableDadosDash" style="font-size: 12px">
              <thead>
                <tr>
                  <th style="color: #231F20" >Forma de Pagamento</th>
                  <th style="color: #231F20" >Qtd.</th>
                  <th style="color: #231F20" >Bruto</th>
                  <th style="color: #231F20" >Taxa</th>
                  <th style="color: #231F20" >Líquido</th>
                  <th style="color: #231F20" >Ticket Médio</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
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
        <h4 class="mt-0" style="text-align: center">Vendas por Produto</h4>
        <div class="row">
          <div class="col-6">
            <div class="dropdown">
              <button class="btn btn-sm dropdown-toggle" style="background: #2D5275; color: white" type="button" id="dropdownMenuButtonProduto" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Escolher Período <i class="mdi mdi-chevron-down"></i>
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonProduto">
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
              <a class="dropdown-toggle pull-right" onclick="gerarPdfVendasProduto()" type="button" id="dropdownMenuButtonAgrupamento" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{ url('/assets/images/export.png')}}" style="width: 45px" alt="">
              </a>
              <!-- <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonAgrupamento">
              <a class="dropdown-item" onclick="trocaAgrupamento(1)">Operadora</a>
              <a class="dropdown-item" onclick="trocaAgrupamento(2)">Bandeira</a>
              <a class="dropdown-item" onclick="trocaAgrupamento(3)">Modalidade</a>
              <a class="dropdown-item" onclick="trocaAgrupamento(4)">Produto</a>
            </div> -->
          </div>
        </div>
      </div>
      <div id="apex_pie9" class="apex-charts"></div>
      <div class="table-responsive mt-4" style="font-size: 13px; overflow-y: auto; max-height: 160px">

        <table id="table_vendas_produto"  class="table table-borderless tableDadosDash" style="font-size: 12px">


          <thead>
            <tr>
              <th style="color: #231F20" >Produto</th>
              <th style="color: #231F20" >Qtd.</th>
              <th style="color: #231F20" >Bruto</th>
              <th style="color: #231F20" >Taxa</th>
              <th style="color: #231F20" >Líquido</th>
              <th style="color: #231F20" >Ticket Médio</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </div><!--end col-->

  </div><!--end row-->
</div><!--end card-body-->
</div><!--end card-->
</div> <!--end col-->

</div> <!--end row-->
<div class="row justify-content-center">

</div><!--end row-->

<div class="row">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-body">
        <!-- <div id='loading'></div> -->


        <div id='calendar' class=""></div>
        <div class="row" style="align-items: center; margin-left: 5px;">
          <div class="circulo" style="margin-right: 5px; background: #257E4A; text-align: right"> </div>
          <h5> Depositado </h5>

          <div class="circulo" style="margin-left: 30px; margin-right: 5px; background: #2D93AD;"> </div>
          <h5> Previsto </h5>
        </div>
      </div><!--end card-body-->
    </div><!--end card-->
  </div><!--end col-->

  <div class="col-lg-5">
    <div class="card">
      <div class="card-body">
        <div id="preloader" style="display: none" class="loaderDash"></div>

        <div class="wallet-bal-usd">
          <h4 class="wallet-title m-0">Recebimentos</h4>
          <span id="label_data_recebimento" class="text-muted font-12"><b style="color: #6E6E6E"><?php echo date("01/m/Y") ?> à <?php echo date("30/m/Y") ?></b></span>
          <h3 id="label_recebimentos" class="text-center" style="color: #257E4A">R$ <?php
          echo number_format( $total_mes->val_liquido ,2,",",".");
          ?> </h3>
        </div> <br>
        <!-- <p class="font-15 text-success text-center mb-4"> + $455.00 <span class="font-12 text-muted">(6.2% <i class="mdi mdi-trending-up text-success"></i>)</span></p> -->
        <ul class="nav nav-pills nav-justified" role="tablist">
          <li class="nav-item waves-effect waves-light">
            <a class="active nav-link  py-3 font-weight-semibold" data-toggle="tab" data-target="#Wallet" role="tab" aria-selected="false"><i data-feather="credit-card" class="align-self-center icon-md mr-2"></i>Operadora</a>
          </li>
          <li class="nav-item waves-effect waves-light">
            <a class=" nav-link py-3 font-weight-semibold" data-toggle="tab" data-target="#Total"  role="tab" aria-selected="true"><i data-feather="home" class="align-self-center icon-md mr-2"></i>Banco</a>
          </li>

        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane p-3" id="Total" role="tabpanel">
            <div class="row">
              <div class="col-12">


              </div>
            </div>
            <ul class="list-group wallet-bal-crypto mt-3" id="ul_bancos">
              @foreach($dados_bancos as $bancos)
              <li class="list-group-item align-items-center d-flex justify-content-between">
                <div class="col-12 row" style="text-align: center">
                  <div class="col-2" style="margin: 0">
                    <img src="{{ $bancos->IMAGEM}}" class="align-self-center" style="width: 45px; margin-left: -20px;">
                  </div>
                  <div class="col-4 media-body align-self-center">
                    <h4 class="" style="font-size: 13px; margin-left: -30px"> AG: {{ $bancos->AGENCIA}} - C/C: {{ $bancos->CONTA }} </h4>
                  </div>

                  <div class="col-5 media-body align-self-center" style="margin: 0">
                    <h4 class="" style="text-align: right; font-size: 14px; color: #257E4A">R$ <?php
                    echo number_format( $bancos->val_liquido ,2,",",".");
                    ?> </h4>

                  </div><!--end media body-->
                  <?php $teste = $bancos->CODIGO ?>
                  <div class="col-1 media-body align-self-center">
                    <!-- <a style="margin-right: -60px" onclick="showTableBancoSelecionado({{$teste}})" data-toggle="tab" href="#div_banco_selecionado"><i class="thumb-lg mdi mdi-chevron-right"></i> </a> -->
                    <!-- <a data-toggle="tab" href="#div_banco_selecionado" role="tab" aria-selected="true"><i class="thumb-lg mdi mdi-chevron-right"></i></a> -->
                    <a id="{{$bancos->CODIGO}}" data-toggle="tab" data-target="#div_banco_selecionado" onclick="showTableBancoSelecionadoInicial({{$teste}})" role="tab" aria-selected="false" style="display: block"><i class="thumb-lg mdi mdi-chevron-right"></i> </a>
                  </div>
                </div>
                <!-- <span class="badge badge-soft-pink">Bitcoin</span> -->
              </li>
              @endforeach

            </ul>
          </div>

          <div class="tab-pane p-3" id="div_banco_selecionado" style="align-items: center; justify-content: center"role="tab">

            <ul class="list-group wallet-bal-crypto mt-3" style="align-items: center; justify-content: center">

              <table  id="table_banco_selecionado" class="table" style="font-size: 14px;">
                <thead>
                </thead>
                <tbody>
                  <!-- @foreach($dados_dash_vendas as $dados_vendas) -->

                  <!-- @endforeach -->
                </tbody>
              </table>
              <br>
              <ul class="nav nav-pills nav-justified" role="tablist">
                <a type="button" id="voltar" data-target="#Total" data-toggle="tab" aria-label="Close"> < Voltar </a>


              </ul>

            </ul>
          </div>

          <div class="tab-pane p-3" id="div_operadora_selecionada" role="tab">

            <ul class="list-group wallet-bal-crypto mt-3" style="align-items: center; justify-content: center">

              <table  id="table_operadora_selecionado" class="table" style="font-size: 14px; text-align: center;">
                <thead>
                </thead>
                <tbody>
                  <!-- @foreach($dados_dash_vendas as $dados_vendas) -->

                  <!-- @endforeach -->
                </tbody>
              </table>
              <br>
              <ul class="nav nav-pills nav-justified" role="tablist">
                <a type="button" id="voltar_operadora" data-target="#Wallet" data-toggle="tab" aria-label="Close"> < Voltar </a>


              </ul>

            </ul>
          </div>

          <div class="tab-pane p-3 active" id="Wallet" role="tabpanel">
            <!-- <div class="row">
            <div class="col-12">
            <div class="wallet-bal-usd">
            <h4 class="wallet-title m-0">Total Recebido</h4>
            <span class="text-muted font-12"><?php echo date("01/m/Y") ?> à <?php echo date("30/m/Y") ?></span>
            <h3 class="text-center" style="color: #01DFA5">R$ <?php
            echo number_format( $total_banco ,2,",",".");
            ?> </h3>
          </div>
          <p class="font-15 text-success text-center mb-4"> + $455.00 <span class="font-12 text-muted">(6.2% <i class="mdi mdi-trending-up text-success"></i>)</span></p>
          <div class="text-right">
          <button class="btn btn-gradient-primary px-3">+ Invest</button>
        </div>
      </div>
    </div> -->
    <ul  id="ul_operadora" class="list-group wallet-bal-crypto mt-3">
      @foreach($dados_operadora as $operadora)
      <li class="list-group-item align-items-center d-flex justify-content-between">
        <div class="col-12 row">
          <img src="{{ $bancos->IMAGEMAD}}" style="width: 70px;" class="align-self-center" alt="...">
          <div class="col-7 media-body align-self-center">
            <!-- <div class="coin-bal row"> -->
            <h4 class="m-0" style="text-align: right; font-size: 14px; color: #257E4A">R$ <?php
            echo number_format( $operadora->val_liquido ,2,",",".");
            ?> </h4>

            <!-- </div> -->
          </div><!--end media body-->
          <div class="col-1 media-body align-self-center">
            <!-- <a style="margin-right: -60px" onclick="showTableBancoSelecionado({{$teste}})" data-toggle="tab" href="#div_banco_selecionado"><i class="thumb-lg mdi mdi-chevron-right"></i> </a> -->
            <!-- <a data-toggle="tab" href="#div_banco_selecionado" role="tab" aria-selected="true"><i class="thumb-lg mdi mdi-chevron-right"></i></a> -->
            <?php $ad = $operadora->CODIGO ?>

            <a id="{{ "operadora".$operadora->CODIGO}}" data-toggle="tab" data-target="#div_operadora_selecionada" onclick="showTableOperadoraSelecionadaInicial({{$ad}})" role="tab" aria-selected="false" style="display: block"><i class="thumb-lg mdi mdi-chevron-right"></i> </a>

          </div>        </div>
          <!-- <span class="badge badge-soft-pink">Bitcoin</span> -->
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</div><!--end card-body-->
</div><!--end card-->
</div>

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
<script src="{{ URL::asset('plugins/moment/moment.js')}}"></script>
<script src="{{ URL::asset('plugins/apexcharts/irregular-data-series.js')}}"></script>
<script src="{{ URL::asset('plugins/apexcharts/ohlc.js')}}"></script>
<!-- <script src="{{ URL::asset('assets/pages/jquery.apexcharts.init.js')}}"></script> -->

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
  preCarregarGraficoVendasBandeira();
  preCarregarGraficoVendasModalidade();
  preCarregarGraficoVendasProduto();

  // página totalmente carregada (DOM, imagens etc.)
});

var periodo = null;
var grafico_vendas_operadora = null;
var grafico_vendas_modalidade = null;
var grafico_vendas_bandeira = null;
var bancos_dados = null
var operadoras_dados = null;

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

  var totalQtd = 0;
  var totalBruto = 0;
  var totalTx = 0;
  var totalLiq = 0;
  var totalTicket = 0;

  dash_vendas = <?php echo $dados_dash_vendas ?>;

  dash_vendas.forEach((dados_dash) => {
    if(dados_dash.COD_PERIODO == 2){

      dados_grafico.push(dados_dash);

      var html = "<tr>";
      html += "<td>"+"<img src='"+dados_dash.IMAGEM+"' style='width: 45px'/>"+"</td>";
      html += "<td style='color: #231F20'>"+dados_dash.QUANTIDADE+"</td>";
      html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_BRUTO)+"</td>";
      html += "<td style='color: red'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_TAXA)+"</td>";
      html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_LIQUIDO)+"</td>";
      html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TICKET_MEDIO)+"</td>";

      totalQtd += parseInt(dados_dash.QUANTIDADE);
      totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
      totalTx += parseFloat(dados_dash.TOTAL_TAXA);
      totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
      totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

      html += "</tr>";

      $('#table_vendas_operadora').append(html);

      document.getElementById("dropdownMenuButton").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
    }
  })
  var htmlSubTotal = "<tr>";

  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+"Total"+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+totalQtd+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalBruto)+"</td>";
  htmlSubTotal += "<td style='color: red; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTx)+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalLiq)+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTicket)+"</td>";

  htmlSubTotal += "</tr>";

  $('#table_vendas_operadora').append(htmlSubTotal);  periodo = 2;

  localStorage.setItem('periodo_venda_operadora', 2);

  geraGraficoVendas(dados_grafico, 1);
}

function preCarregarGraficoVendasBandeira(){
  var dados_grafico = [];

  var totalQtd = 0;
  var totalBruto = 0;
  var totalTx = 0;
  var totalLiq = 0;
  var totalTicket = 0;

  dash_vendas = <?php echo $dados_dash_vendas_bandeira ?>;

  dash_vendas.forEach((dados_dash) => {
    if(dados_dash.COD_PERIODO == 2 && dados_dash.QUANTIDADE > 0){

      dados_grafico.push(dados_dash);

      var html = "<tr>";

      html += "<td>"+"<img src='"+dados_dash.IMAGEM+"' style='width: 28px'/>"+"</td>";
      html += "<td style='color: #231F20'>"+dados_dash.QUANTIDADE+"</td>";
      html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_BRUTO)+"</td>";
      html += "<td style='color: red'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_TAXA)+"</td>";
      html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_LIQUIDO)+"</td>";
      html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TICKET_MEDIO)+"</td>";
      html += "</tr>";

      totalQtd += parseInt(dados_dash.QUANTIDADE);
      totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
      totalTx += parseFloat(dados_dash.TOTAL_TAXA);
      totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
      totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

      $('#table_vendas_bandeira').append(html);

      document.getElementById("dropdownMenuButtonBandeira").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
    }
  })

  var htmlSubTotal = "<tr>";

  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+"Total"+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+totalQtd+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalBruto)+"</td>";
  htmlSubTotal += "<td style='color: red; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTx)+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalLiq)+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTicket)+"</td>";

  htmlSubTotal += "</tr>";

  $('#table_vendas_bandeira').append(htmlSubTotal);

  // dropdownMenuButton
  periodo = 2;

  localStorage.setItem('periodo_venda_bandeira', 2);


  geraGraficoVendasBandeira(dados_grafico, 1);
}

function preCarregarGraficoVendasProduto(){
  var dados_grafico = [];

  var totalQtd = 0;
  var totalBruto = 0;
  var totalTx = 0;
  var totalLiq = 0;
  var totalTicket = 0;

  dash_vendas_produto = <?php echo $dados_dash_vendas_produto ?>;

  dash_vendas_produto.forEach((dados_dash) => {
    if(dados_dash.COD_PERIODO == 2 && dados_dash.QUANTIDADE > 0){

      dados_grafico.push(dados_dash);

      var html = "<tr>";

      html += "<td style='color: #231F20'>"+dados_dash.PRODUTO_WEB+"</td>";
      html += "<td style='color: #231F20'>"+dados_dash.QUANTIDADE+"</td>";
      html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_BRUTO)+"</td>";
      html += "<td style='color: red'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_TAXA)+"</td>";
      html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_LIQUIDO)+"</td>";
      html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TICKET_MEDIO)+"</td>";
      html += "</tr>";

      totalQtd += parseInt(dados_dash.QUANTIDADE);
      totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
      totalTx += parseFloat(dados_dash.TOTAL_TAXA);
      totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
      totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

      $('#table_vendas_produto').append(html);

      document.getElementById("dropdownMenuButtonProduto").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
    }
  })

  var htmlSubTotal = "<tr>";

  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+"Total"+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+totalQtd+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalBruto)+"</td>";
  htmlSubTotal += "<td style='color: red; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTx)+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalLiq)+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTicket)+"</td>";

  htmlSubTotal += "</tr>";

  $('#table_vendas_produto').append(htmlSubTotal);

  // dropdownMenuButton
  periodo = 2;

  localStorage.setItem('periodo_venda_produto', 2);


  geraGraficoVendasProduto(dados_grafico, 1);
}

function preCarregarGraficoVendasModalidade(){
  var dados_grafico = [];

  var totalQtd = 0;
  var totalBruto = 0;
  var totalTx = 0;
  var totalLiq = 0;
  var totalTicket = 0

  dashboard_vendas_modalidade = <?php echo $dados_dash_vendas_modalidade ?>;
  $('#table_vendas_modalidade tbody').empty();

  dashboard_vendas_modalidade.forEach((dados_dash) => {

    if(dados_dash.COD_PERIODO == 2 && dados_dash.QUANTIDADE > 0){

      var html = "<tr>";

      html += "<td>"+dados_dash.DESCRICAO+"</td>";
      html += "<td style='color: #231F20'>"+dados_dash.QUANTIDADE+"</td>";
      html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_BRUTO)+"</td>";
      html += "<td style='color: red'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_TAXA)+"</td>";
      html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_LIQUIDO)+"</td>";
      html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TICKET_MEDIO)+"</td>";
      html += "</tr>";

      $('#table_vendas_modalidade').append(html);

      totalQtd += parseInt(dados_dash.QUANTIDADE);
      totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
      totalTx += parseFloat(dados_dash.TOTAL_TAXA);
      totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
      totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

      dados_grafico.push(dados_dash);
      document.getElementById("dropdownMenuButtonModalidade").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
    }
  })

  var htmlSubTotal = "<tr>";

  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+"Total"+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+totalQtd+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalBruto)+"</td>";
  htmlSubTotal += "<td style='color: red; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTx)+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalLiq)+"</td>";
  htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTicket)+"</td>";

  htmlSubTotal += "</tr>";

  $('#table_vendas_modalidade').append(htmlSubTotal);

  periodo = 2;

  localStorage.setItem('periodo_venda_modalidade', 2);


  geraGraficoVendasModalidade(dados_grafico);

}

function trocaPeriodo(cod_periodo, tipo){
  dados_grafico = [];

  dash_vendas = <?php echo $dados_dash_vendas ?>;

  var totalQtd = 0;
  var totalBruto = 0;
  var totalTx = 0;
  var totalLiq = 0;
  var totalTicket = 0

  if(tipo == 'operadora'){
    dash_vendas = <?php echo $dados_dash_vendas ?>;

    $('#table_vendas_operadora tbody').empty();

    dash_vendas.forEach((dados_dash) => {

      if(dados_dash.COD_PERIODO == cod_periodo && dados_dash.QUANTIDADE > 0){

        var html = "<tr>";

        html += "<td>"+"<img src='"+dados_dash.IMAGEM+"' style='width: 45px'/>"+"</td>";
        html += "<td style='color: #231F20'>"+dados_dash.QUANTIDADE+"</td>";
        html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_BRUTO)+"</td>";
        html += "<td style='color: red'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_TAXA)+"</td>";
        html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_LIQUIDO)+"</td>";
        html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TICKET_MEDIO)+"</td>";

        html += "</tr>";

        $('#table_vendas_operadora').append(html);

        totalQtd += parseInt(dados_dash.QUANTIDADE);
        totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
        totalTx += parseFloat(dados_dash.TOTAL_TAXA);
        totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
        totalTicket += parseFloat(dados_dash.TICKET_MEDIO);
        dados_grafico.push(dados_dash);
        document.getElementById("dropdownMenuButton").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
      }
    })

    var htmlSubTotal = "<tr>";

    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+"Total"+"</td>";
    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+totalQtd+"</td>";
    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalBruto)+"</td>";
    htmlSubTotal += "<td style='color: red; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTx)+"</td>";
    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalLiq)+"</td>";
    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTicket)+"</td>";

    htmlSubTotal += "</tr>";

    $('#table_vendas_operadora').append(htmlSubTotal);

    if(dados_grafico.length == 0){
      console.log("VAZIOOOOOOOOOOOO");
    }else{
      grafico_vendas_operadora.destroy();

      periodo = cod_periodo;

      localStorage.setItem('periodo_venda_operadora', cod_periodo);

      geraGraficoVendas(dados_grafico);
    }

  }else if(tipo == 'bandeira'){
    dash_vendas = <?php echo $dados_dash_vendas_bandeira ?>;

    $('#table_vendas_bandeira tbody').empty();

    dash_vendas.forEach((dados_dash) => {

      if(dados_dash.COD_PERIODO == cod_periodo && dados_dash.QUANTIDADE > 0){

        var html = "<tr>";

        html += "<td>"+"<img src='"+dados_dash.IMAGEM+"' style='width: 28px'/>"+"</td>";
        html += "<td style='color: #231F20'>"+dados_dash.QUANTIDADE+"</td>";
        html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_BRUTO)+"</td>";
        html += "<td style='color: red'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_TAXA)+"</td>";
        html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_LIQUIDO)+"</td>";
        html += "<td style='color: #231F20'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TICKET_MEDIO)+"</td>";

        html += "</tr>";

        $('#table_vendas_bandeira').append(html);

        totalQtd += parseInt(dados_dash.QUANTIDADE);
        totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
        totalTx += parseFloat(dados_dash.TOTAL_TAXA);
        totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
        totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

        dados_grafico.push(dados_dash);

        document.getElementById("dropdownMenuButtonBandeira").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
      }
    })

    var htmlSubTotal = "<tr>";

    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+"Total"+"</td>";
    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+totalQtd+"</td>";
    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalBruto)+"</td>";
    htmlSubTotal += "<td style='color: red; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTx)+"</td>";
    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalLiq)+"</td>";
    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTicket)+"</td>";

    htmlSubTotal += "</tr>";

    $('#table_vendas_bandeira').append(htmlSubTotal);


    if(dados_grafico.length == 0){
      console.log("VAZIOOOOOOOOOOOO");
    }else{
      grafico_vendas_bandeira.destroy();

      periodo = cod_periodo;

      localStorage.setItem('periodo_venda_bandeira', 2);

      geraGraficoVendasBandeira(dados_grafico);
    }

  }else if(tipo == 'modalidade'){
    dash_vendas = <?php echo $dados_dash_vendas_modalidade ?>;

    $('#table_vendas_modalidade tbody').empty();
    dash_vendas.forEach((dados_dash) => {

      if(dados_dash.COD_PERIODO == cod_periodo && dados_dash.QUANTIDADE > 0){

        var html = "<tr>";

        html += "<td>"+dados_dash.DESCRICAO+"</td>";
        html += "<td>"+dados_dash.QUANTIDADE+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_BRUTO)+"</td>";
        html += "<td style='color: red'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_TAXA)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(dados_dash.TOTAL_LIQUIDO)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(dados_dash.TICKET_MEDIO)+"</td>";

        html += "</tr>";

        $('#table_vendas_modalidade').append(html);

        totalQtd += parseInt(dados_dash.QUANTIDADE);
        totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
        totalTx += parseFloat(dados_dash.TOTAL_TAXA);
        totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
        totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

        dados_grafico.push(dados_dash);

        document.getElementById("dropdownMenuButtonModalidade").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
      }
    })

    var htmlSubTotal = "<tr>";

    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+"Total"+"</td>";
    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+totalQtd+"</td>";
    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalBruto)+"</td>";
    htmlSubTotal += "<td style='color: red; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTx)+"</td>";
    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalLiq)+"</td>";
    htmlSubTotal += "<td style='color: #6E6E6E; font-weight: bold'>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(totalTicket)+"</td>";

    htmlSubTotal += "</tr>";

    $('#table_vendas_modalidade').append(htmlSubTotal);


    if(dados_grafico.length == 0){
      console.log("VAZIOOOOOOOOOOOO");
    }else{
      grafico_vendas_modalidade.destroy();

      periodo = cod_periodo;

      localStorage.setItem('periodo_venda_modalidade', cod_periodo);

      geraGraficoVendasModalidade(dados_grafico);
    }

  }else{

    $('#table_vendas_operadora tbody').empty();

    dash_vendas.forEach((dados_dash) => {

      if(dados_dash.COD_PERIODO == cod_periodo && dados_dash.QUANTIDADE > 0){

        var html = "<tr>";

        html += "<td>"+dados_dash.QUANTIDADE+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio)+"</td>";

        html += "</tr>";

        $('#table_vendas_operadora').append(html);

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

      geraGraficoVendas(dados_grafico);
    }

  }

  // }else if(grupo == "Bandeira"){
  //   dash_vendas = <?php echo $dados_dash_vendas_bandeira ?>;
  //   //
  //   // var total_bruto = 0;
  //   // var total_liquido = 0;
  //   // var total_taxa = 0;
  //   // var total_ticket_medio = 0;
  //   // var qtde = 0;
  //   $('#table_vendas tbody').empty();
  //
  //   dash_vendas.forEach((dados_dash) => {
  //
  //     if(dados_dash.COD_PERIODO == cod_periodo){
  //       console.log("TESTEEEEEEE");
  //       // bruto = dados_dash.TOTAL_BRUTO;
  //       // liquido = dados_dash.TOTAL_LIQUIDO;
  //       // taxa = dados_dash.TOTAL_TAXA;
  //       // ticket = dados_dash.TICKET_MEDIO;
  //       //
  //       total_bruto = parseInt(dados_dash.TOTAL_BRUTO);
  //       total_liquido = parseInt(dados_dash.TOTAL_LIQUIDO);
  //       total_taxa = parseInt(dados_dash.TOTAL_TAXA);
  //       total_ticket_medio = parseInt(dados_dash.TICKET_MEDIO);
  //       qtde = parseInt(dados_dash.QUANTIDADE);
  //
  //       // document.getElementById("venda_total_bruto").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto);
  //       // document.getElementById("venda_total_taxa").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa);
  //       // document.getElementById("venda_total_liquido").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido);
  //       // document.getElementById("venda_ticket_medio").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio);
  //
  //
  //       var html = "<tr>";
  //
  //       html += "<td>"+dados_dash.QUANTIDADE+"</td>";
  //       html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto)+"</td>";
  //       html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa)+"</td>";
  //       html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido)+"</td>";
  //       html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio)+"</td>";
  //
  //       html += "</tr>";
  //
  //       $('#table_vendas').append(html);
  //
  //       dados_grafico.push(dados_dash);
  //
  //       document.getElementById("dropdownMenuButton").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
  //     }
  //   })
  //
  //   if(dados_grafico.length == 0){
  //     console.log("VAZIOOOOOOOOOOOO");
  //   }else{
  //     grafico_vendas.destroy();
  //     //
  //     // document.getElementById("venda_total_bruto").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto);
  //     // document.getElementById("venda_total_taxa").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa);
  //     // document.getElementById("venda_total_liquido").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido);
  //     // document.getElementById("venda_ticket_medio").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio);
  //     // document.getElementById("quantidade").innerHTML = qtde;
  //
  //     periodo = cod_periodo;
  //     geraGraficoVendas(dados_grafico, 2);
  //   }
  // }else if(grupo == "Modalidade"){
  //   dash_vendas = <?php echo $dados_dash_vendas_modalidade ?>;
  //
  //   var total_bruto = 0;
  //   var total_liquido = 0;
  //   var total_taxa = 0;
  //   var total_ticket_medio = 0;
  //   var qtde = 0;
  //
  //   dash_vendas.forEach((dados_dash) => {
  //     console.log(dash_vendas);
  //
  //     if(dados_dash.COD_PERIODO == cod_periodo){
  //
  //       bruto = dados_dash.TOTAL_BRUTO;
  //       liquido = dados_dash.TOTAL_LIQUIDO;
  //       taxa = dados_dash.TOTAL_TAXA;
  //       ticket = dados_dash.TICKET_MEDIO;
  //
  //       total_bruto = parseInt(total_bruto) + parseInt(dados_dash.TOTAL_BRUTO);
  //       total_liquido = parseInt(total_liquido) + parseInt(dados_dash.TOTAL_LIQUIDO);
  //       total_taxa = parseInt(total_taxa) + parseInt(dados_dash.TOTAL_TAXA);
  //       total_ticket_medio = parseInt(total_ticket_medio) + parseInt(dados_dash.TICKET_MEDIO);
  //       qtde = qtde + parseInt(dados_dash.QUANTIDADE);
  //
  //       dados_grafico.push(dados_dash);
  //
  //       document.getElementById("dropdownMenuButton").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
  //     }
  //   })
  //
  //   if(dados_grafico.length == 0){
  //     console.log("VAZIOOOOOOOOOOOO");
  //   }else{
  //     grafico_vendas.destroy();
  //
  //     document.getElementById("venda_total_bruto").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto);
  //     document.getElementById("venda_total_taxa").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa);
  //     document.getElementById("venda_total_liquido").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido);
  //     document.getElementById("venda_ticket_medio").innerHTML = Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio);
  //     document.getElementById("quantidade").innerHTML = qtde;
  //     periodo = cod_periodo;
  //     geraGraficoVendas(dados_grafico, 3);
  //   }
  //
  // }else if(grupo = "Produto"){
  //
  // }
  // }
}
$("voltar").click(function() {
  $("div_banco_selecionado").removeClass('div_banco_selecionado');
});

$("voltar_operadora").click(function() {
  $("div_operadora_selecionada").removeClass('div_operadora_selecionada');
});

function showTableBancoSelecionado(codigo){
  $("#table_banco_selecionado tbody").empty();

  var bancos = bancos_dados;

  var result = bancos.find(banco => banco.CODIGO == codigo);

  var val_bruto = parseFloat(result.val_bruto);
  var val_liquido = parseFloat(result.val_liquido);
  var tx = parseInt(result.TAXA_PERCENTUAL);
  var t = Number(tx).toFixed(2);
  console.log(result);
  var html = "<tr>";

  html += "<td>"+"<b text-align='left'>Recebíveis Bruto:  </b>" +Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(val_bruto)+"</td>";


  html += "</tr>";

  html += "<tr>";

  html += "<td style='color: red'>"+"<b style='color: black'>Taxas: </b> "+ Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(tx)+"</b>"+"</td>";


  html += "</tr>";

  html += "<tr>";

  html += "<td>"+"<b> Tarifas Extras: </b>"+"</td>";


  html += "</tr>";

  html += "<tr>";


  html += "<td>"+"<b>Valor Líquido: </b>"+ Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(val_liquido)+"</td>";

  html += "</tr>";

  html += "<tr>";


  html += "<td style='background: #BDBDBD '>"+"<b>Situação de Pagamento: "+"</td>";

  html += "</tr>";

  $('#table_banco_selecionado').append(html);
  document.getElementById(result.CODIGO).classList.remove('active');
  document.getElementById("voltar").classList.remove('active');



  // document.getElementById("Total").style.display = "block";
}

function showTableOperadoraSelecionada(codigo){
  $("#table_operadora_selecionado tbody").empty();

  var operadoras = operadoras_dados;

  var result = operadoras.find(operadora => operadora.CODIGO == codigo);


  var val_bruto = parseInt(result.val_bruto);
  var val_liquido = parseInt(result.val_liquido);
  var tx = parseInt(result.TAXA_PERCENTUAL);
  var t = Number(tx).toFixed(2);

  console.log(result);
  var html = "<tr>";

  html += "<td>"+"<b text-align='left'>Recebíveis Bruto:  </b>" +Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(val_bruto)+"</td>";


  html += "</tr>";

  html += "<tr>";

  html += "<td style='color: red'>"+"<b style='color: black'>Taxas: </b> "+ Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(tx)+"</b>"+"</td>";


  html += "</tr>";

  html += "<tr>";

  html += "<td>"+"<b> Tarifas Extras: </b>"+"</td>";


  html += "</tr>";

  html += "<tr>";


  html += "<td>"+"<b>Valor Líquido: </b>"+ Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(val_liquido)+"</td>";

  html += "</tr>";

  html += "<tr>";


  html += "<td style='background: #BDBDBD '>"+"<b>Situação de Pagamento: "+"</td>";

  html += "</tr>";

  $('#table_operadora_selecionado').append(html);
  document.getElementById("operadora"+result.CODIGO).classList.remove('active');
  document.getElementById("voltar_operadora").classList.remove('active');
}

function showRecebiveis(data, title){
  // var arrayBancos = [];
  //
  // var pagamentos = <?php echo $dados_calendario_pagamento ?>;
  //
  // var bancos = <?php echo $dados_bancos ?>;
  //
  // var result = pagamentos.find(pagamento => pagamento.CODIGO == codigo);
  // //
  var data_v = new Date(data);
  //
  //
  var data_venda = data_v.toLocaleDateString('pt-BR', {timeZone: 'UTC'});

  document.getElementById("label_recebimentos").innerHTML = title;
  document.getElementById("label_data_recebimento").innerHTML = '<b style="color: #6E6E6E">' + data_venda + '</b>';
  $("#ul_bancos li").remove();
  $("#ul_operadora li").remove();


  var url = "{{ url('detalhe-calendario') }}" + "/" + data;

  $.ajax({
    url: url,
    type: "GET",
    dataType: 'json',
    success: function(response){
      console.log(response)

      bancos_dados = response[0];
      operadoras_dados = response[1];
      // $('#ul_bancos').empty();
      response[0].forEach((bancos) => {
        var html = "<li class='list-group-item align-items-center d-flex justify-content-between'>"

        html += "<div class='col-12 row' style='text-align: center'>"
        html += "<div class='col-2' style='margin: 0'>"
        html += "<img src='" + bancos.IMAGEM + "' class='align-self-center' style='width: 45px; margin-left: -20px;'>"
        html += "</div>"
        html += "<div class='col-4 media-body align-self-center'>"
        html += "<h4 style='font-size: 13px; margin-left: -30px'>" + "AG: " + bancos.AGENCIA + "- C/C: " + bancos.CONTA + "</h4>"
        html += "</div>"
        html += "<div class='col-5 media-body align-self-center' style='margin: 0'>"
        html += "<h4 style='text-align: right; font-size: 14px; color: #257E4A'>" + Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(bancos.val_liquido) + "</h4>"
        html += "</div>"
        html += "<div class='col-1 media-body align-self-center'>"
        html += "<a id='" + bancos.CODIGO + "' data-toggle='tab' data-target='#div_banco_selecionado' onclick='showTableBancoSelecionado(" + bancos.CODIGO + ")' role='tab' aria-selected='false' style='display: block'><i class='thumb-lg mdi mdi-chevron-right'></i> </a>"
        html += "</div>"
        html += "</div>"
        html += "</li>"

        $('#ul_bancos').append(html);
      })

      response[1].forEach((bancos) => {
        var html = "<li class='list-group-item align-items-center d-flex justify-content-between'>"

        html += "<div class='col-12 row'>"
        html += "<img src='" + bancos.IMAGEMAD + "' class='align-self-center' style='width: 70px;'>"
        html += "<div class='col-7 media-body align-self-center'>"
        html += "<h4 class='m-0' style='font-size: 14px; text-align:right; color: #257E4A'>" + Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(bancos.val_liquido)  + "</h4>"
        html += "</div>"
        html += "<div class='col-1 media-body align-self-center'>"
        html += "<a id='operadora" + bancos.CODIGO + "' data-toggle='tab' data-target='#div_banco_selecionado' onclick='showTableOperadoraSelecionada(" + bancos.CODIGO + ")' role='tab' aria-selected='false' style='display: block'><i class='thumb-lg mdi mdi-chevron-right'></i> </a>"
        html += "</div>"
        html += "</div>"
        html += "</li>"

        $('#ul_operadora').append(html);
      })

      // })
    }
  }).fail(function () {
    alert("ErroOOOOOOOOO");
    return;
  });

  document.getElementById("preloader").style.display = "none";

}

function gerarPdfVendasOperadora(){
  var codigo_periodo = localStorage.getItem('periodo_venda_operadora');
  var url = "{{ url('export-vendasoperadora')}}" + "/" + codigo_periodo;

  window.location.href = url;
}

function gerarPdfVendasBandeira(){
  var codigo_periodo = localStorage.getItem('periodo_venda_bandeira');
  var url = "{{ url('export-vendasbandeira')}}" + "/" + codigo_periodo;

  window.location.href = url;
}

function gerarPdfVendasModalidade(){
  var codigo_periodo = localStorage.getItem('periodo_venda_modalidade');
  var url = "{{ url('export-vendasmodalidade')}}" + "/" + codigo_periodo;

  window.location.href = url;
}

function gerarPdfVendasProduto(){
  var codigo_periodo = localStorage.getItem('periodo_venda_produto');
  var url = "{{ url('export-vendasproduto')}}" + "/" + codigo_periodo;

  window.location.href = url;
}


function showTableBancoSelecionadoInicial(codigo){
  $("#table_banco_selecionado tbody").empty();

  var bancos = <?php echo $dados_bancos ?>;

  var result = bancos.find(banco => banco.CODIGO == codigo);
  var val_bruto = parseInt(result.val_bruto);
  var val_liquido = parseInt(result.val_liquido);
  var tx = parseInt(result.TAXA_PERCENTUAL);
  var t = Number(tx).toFixed(2);
  console.log("kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk");

  var html = "<tr>";

  html += "<td>"+"<b text-align='left'>Recebíveis Bruto:  </b>" +Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(val_bruto)+"</td>";


  html += "</tr>";

  html += "<tr>";

  html += "<td style='color: red'>"+"<b style='color: black'>Taxas: </b> "+ Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(tx)+"</b>"+"</td>";


  html += "</tr>";

  html += "<tr>";

  html += "<td>"+"<b> Tarifas Extras: </b>"+"</td>";


  html += "</tr>";

  html += "<tr>";


  html += "<td>"+"<b>Valor Líquido: </b>"+ Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(val_liquido)+"</td>";

  html += "</tr>";

  html += "<tr>";


  html += "<td style='background: #BDBDBD '>"+"<b>Situação de Pagamento: "+"</td>";

  html += "</tr>";

  $('#table_banco_selecionado').append(html);
  document.getElementById(result.CODIGO).classList.remove('active');
  document.getElementById("voltar").classList.remove('active');



  // document.getElementById("Total").style.display = "block";
}

function showTableOperadoraSelecionadaInicial(codigo){
  $("#table_operadora_selecionado tbody").empty();

  var operadoras = <?php echo $dados_operadora ?>;

  var result = operadoras.find(operadora => operadora.CODIGO == codigo);


  var val_bruto = parseInt(result.val_bruto);
  var val_liquido = parseInt(result.val_liquido);
  var tx = parseInt(result.TAXA_PERCENTUAL);
  var t = Number(tx).toFixed(2);

  console.log(result);
  var html = "<tr>";

  html += "<td>"+"<b text-align='left'>Recebíveis Bruto:  </b>" +Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(val_bruto)+"</td>";


  html += "</tr>";

  html += "<tr>";

  html += "<td style='color: red'>"+"<b style='color: black'>Taxas: </b> "+ Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(tx)+"</b>"+"</td>";


  html += "</tr>";

  html += "<tr>";

  html += "<td>"+"<b> Tarifas Extras: </b>"+"</td>";


  html += "</tr>";

  html += "<tr>";


  html += "<td>"+"<b>Valor Líquido: </b>"+ Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(val_liquido)+"</td>";

  html += "</tr>";

  html += "<tr>";


  html += "<td style='background: #BDBDBD '>"+"<b>Situação de Pagamento: "+"</td>";

  html += "</tr>";

  $('#table_operadora_selecionado').append(html);
  document.getElementById("operadora"+result.CODIGO).classList.remove('active');
  document.getElementById("voltar_operadora").classList.remove('active');
}

</script>
@stop
