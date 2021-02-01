@extends('layouts.analytics-master')

@section('title', 'Metrica - Admin & Dashboard Template')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/dashboard/dashboard.css')}}" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>
<link href='lib/main.css' rel='stylesheet' />
<script src='lib/main.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const calendarEl = document.getElementById('calendar');
  const dados_prev_pagamento = <?php echo $dados_calendario ?>;
  const dados_pagamento = <?php echo $dados_calendario_pagamento ?>;
  const data_atual = "{{ $data }}";

  let eventsList = [];

  dados_pagamento.forEach((pagamento) => {
    const hoje = new Date().toISOString().slice(0,10);

    if (pagamento.DATA_PAGAMENTO != hoje){
      const total_liq = Intl.NumberFormat('pt-br', {
        style: 'currency',
        currency: 'BRL'
      }).format(pagamento.val_liquido);
      eventsList.push({
        title: total_liq,
        description: pagamento.CODIGO,
        start: pagamento.DATA_PAGAMENTO,
        color: '#257e4a',
        background: '#FF4000',
        publicId: pagamento.DATA_PAGAMENTO
      }, );
    }
  });

  let eventos = eventsList;

  dados_prev_pagamento.forEach((previsao_pagamento) => {
    const total_liq_prev_pagt = Intl.NumberFormat('pt-br', {
      style: 'currency',
      currency: 'BRL'
    }).format(previsao_pagamento.val_liquido);

    if (previsao_pagamento.DATA_PREVISTA_PAGTO >= data_atual) {
      eventsList.push({
        title: total_liq_prev_pagt,
        description: previsao_pagamento.CODIGO,
        start: previsao_pagamento.DATA_PREVISTA_PAGTO,
        color: '#2D93AD',
        publicId: previsao_pagamento.DATA_PREVISTA_PAGTO
      }, );
    }
  });

  var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: ''
    },
    height: 550,
    navLinks: false,
    businessHours: true,
    // editable: true,
    // selectable: true,

    events: eventsList,
    eventClick: function(calEvent, jsEvent, view) {
      if (calEvent.event._def.extendedProps.publicId) {
        const color = calEvent.event._def.ui.backgroundColor;
        const data_clicada = calEvent.event._def.extendedProps.publicId;
        const valor = calEvent.event._def.title;

        showRecebiveis(data_clicada, valor, color, jsEvent);
      }
    }
  });

  $(".fc-prev-button").append('<i class="glyphicon"...</i>')

  calendar.render();
});
</script>

<style>
/* body {
margin: 40px 10px;
padding: 0;
font-family: Arial, Helvetica Neue, Helvetica, sans-serif !important;
font-size: 14px;
} */

#calendar {
  max-width: 1100px;
  margin: 0 auto;
}
</style>
@stop

@section('content')
<div id="dashboard_styles" class="container-fluid">
  <div class="modal fade" id="modal-alerta-global" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalCupom" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modalCupom">AVISO GERAL</h3>
          <button type="button" style="color: white" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4>{{ $frase->ALERTA_GLOBAL }}</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Fechar</b></button>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12" style="margin-top: 15px;">
      <div class="row" style="align-items: center; justify-content: center;">
        <div class="col-md-6 col-lg-3">
          <div class="card report-card">
            <div class="card-body" style="height: 140px">
              <div class="row d-flex">
                <div class="col-12">
                  <p class="font-weight-semibold font-12">Suporte</p>
                </div>
                <div class="col-12" style="margin-top: -20px">
                  <div class="row">
                    <div class="col-12" align="center">
                      <img src="{{ url('assets/images/suporte.png') }}" style="width: 27px; text-align: center" alt="">
                      <h5 style="font-size: 13px" id="total_taxa_cobrada" class="my-3">(44) 3020-0220</h5>
                    </div>
                  </div>
                  <div class="col-12" style="margin-top: -20px">
                    <h6 style="font-size: 12px" id="total_taxa_cobrada" class="my-3">Atendimento | Segunda a sexta-feira - das 08:00h às 18:00h</h6>
                  </div>

                  <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card report-card">
            <div class="card-body" style="height: 140px">
              <div class="row d-flex">
                <div class="col-12">
                  <p class="font-weight-semibold font-12">Divergências - Conc. de vendas</p>
                  <h6 style="font-size: 12px" id="total_taxa_cobrada" class="my-3">Não há divergências no momento</h6>
                  <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card report-card">
            <div class="card-body" style="height: 140px">
              <div class="row d-flex">
                <div class="col-12">
                  <p class="font-weight-semibold font-12">Divergências - Taxas</p>
                  <h6 style="font-size: 12px" id="total_taxa_cobrada" class="my-3">Não há divergências no momento</h6>
                  <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card report-card">
            <div class="card-body" style="height: 140px">
              <div class="row d-flex">
                <div class="col-12">
                  <p class="font-weight-semibold font-12">Divergências - Conc. Bancária</p>
                  <h6 style="font-size: 12px" id="total_taxa_cobrada" class="my-3">Não há divergências no momento</h6>
                  <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      @component('common-components.breadcrumb')
      @slot('title') Gerencial @endslot
      @slot('item1') Dashboard @endslot
      @endcomponent
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <div class="row">
            @if(isset($dados_dash_vendas))
            <div class="col-lg-12">
              <h4 class="mt-0" style="text-align: center">Vendas por Operadora</h4>
              <div class="row">
                <div class="col-6">
                  <div class="dropdown">
                    <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" onclick="trocaPeriodo(1, 'operadora', 'Ontem')">Ontem</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(2, 'operadora', 'Últimos 7 dias')">Últimos 7 dias</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(3, 'operadora', 'Últimos 15 dias')">Últimos 15 dias</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(4, 'operadora', 'Últimos 30 dias')">Últimos 30 dias</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(5, 'operadora', 'Mês Atual')">Mês Atual</a>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="dropdown div-bt-export">
                    <a class="dropdown-toggle pull-right" onclick="gerarPdfVendasOperadora()" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ url('/assets/images/export.png')}}" class="img-export">
                    </a>
                  </div>
                </div>
              </div>
              <div id="apex_pie2" class="apex-charts"></div>
              <div class="table-responsive mt-4">
                <table id="table_vendas_operadora" class="table table-borderless tableDadosDash">
                  <thead>
                    <tr>
                      <th>Operadora</th>
                      <th>Qtd.</th>
                      <th>Bruto</th>
                      <th>Taxa</th>
                      <th>Líquido</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <h4 class="mt-0" style="text-align: center">Vendas por Bandeira</h4>
              <div class="row">
                <div class="col-6">
                  <div class="dropdown">
                    <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButtonBandeira" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Escolher Período <i class="mdi mdi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" onclick="trocaPeriodo(1, 'bandeira', 'Ontem')">Ontem</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(2, 'bandeira', 'Últimos 7 dias')">Últimos 7 dias</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(3, 'bandeira', 'Últimos 15 dias')">Últimos 15 dias</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(4, 'bandeira', 'Últimos 30 dias')">Últimos 30 dias</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(5, 'bandeira', 'Mês Atual')">Mês Atual</a>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="dropdown div-bt-export">
                    <a class="dropdown-toggle pull-right" onclick="gerarPdfVendasBandeira()" type="button" id="dropdownMenuButtonAgrupamento" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ url('/assets/images/export.png')}}" class="img-export">
                    </a>
                  </div>
                </div>
              </div>
              <div id="apex_pie7" class="apex-charts"></div>
              <div class="table-responsive mt-4">
                <table id="table_vendas_bandeira" class="table table-borderless tableDadosDash">
                  <thead>
                    <tr>
                      <th>Bandeira</th>
                      <th>Qtd.</th>
                      <th>Bruto</th>
                      <th>Taxa</th>
                      <th>Líquido</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <div class="row">
            @if(isset($dados_dash_vendas))
            <div class="col-lg-12">
              <h4 class="mt-0" style="text-align: center">Vendas por Forma de Pagamento</h4>
              <div class="row">
                <div class="col-6">
                  <div class="dropdown">
                    <button class="btn btn-sm dropdown-toggle" id="dropdownMenuButtonModalidade" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" onclick="trocaPeriodo(1, 'modalidade', 'Ontem')">Ontem</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(2, 'modalidade', 'Últimos 7 dias')">Últimos 7 dias</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(3, 'modalidade', 'Últimos 15 dias')">Últimos 15 dias</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(4, 'modalidade', 'Últimos 30 dias')">Últimos 30 dias</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(5, 'modalidade', 'Mês Atual')">Mês Atual</a>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="dropdown div-bt-export">
                    <a class="dropdown-toggle pull-right" onclick="gerarPdfVendasModalidade()" type="button" id="dropdownMenuButtonAgrupamento" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ url('/assets/images/export.png')}}" class="img-export">
                    </a>
                  </div>
                </div>
              </div>
              <div id="apex_pie8" class="apex-charts"></div>
              <div class="table-responsive mt-4">
                <table id="table_vendas_modalidade" class="table table-borderless tableDadosDash">
                  <thead>
                    <tr>
                      <th>Forma de Pagamento</th>
                      <th>Qtd.</th>
                      <th>Bruto</th>
                      <th>Taxa</th>
                      <th>Líquido</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <h4 class="mt-0" style="text-align: center">Vendas por Produto</h4>
              <div class="row">
                <div class="col-6">
                  <div class="dropdown">
                    <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButtonProduto" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Escolher Período <i class="mdi mdi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonProduto">
                      <a class="dropdown-item" onclick="trocaPeriodo(1, 'produto', 'Ontem')">Ontem</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(2, 'produto', 'Últimos 7 dias')">Últimos 7 dias</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(3, 'produto', 'Últimos 15 dias')">Últimos 15 dias</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(4, 'produto', 'Últimos 30 dias')">Últimos 30 dias</a>
                      <a class="dropdown-item" onclick="trocaPeriodo(5, 'produto', 'Mês Atual')">Mês Atual</a>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="dropdown div-bt-export">
                    <a class="dropdown-toggle pull-right" onclick="gerarPdfVendasProduto()" type="button" id="dropdownMenuButtonAgrupamento" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ url('/assets/images/export.png')}}" class="img-export">
                    </a>
                  </div>
                </div>
              </div>
              <div id="apex_pie9" class="apex-charts"></div>
              <div class="table-responsive mt-4">
                <table id="table_vendas_produto" class="table table-borderless tableDadosDash">
                  <thead>
                    <tr>
                      <th>Produto</th>
                      <th>Qtd.</th>
                      <th>Bruto</th>
                      <th>Taxa</th>
                      <th>Líquido</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-body">
          <div id='calendar'></div>
          <div class="row legenda-calendario">
            <div class="circulo"> </div>
            <h5> Depositado </h5>
            <div class="circulo previsto"> </div>
            <h5> Previsto </h5>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card">
        <div class="card-body">
          <div id="preloader" style="display: none" class="loader"></div>

          <div class="wallet-bal-usd">
            <div class="row recebimentos">
              <div class="col-6">
                <h4 class="wallet-title m-0">Recebimentos</h4>
                <span id="label_data_recebimento" class="text-muted font-12"><b><?php echo date("d/m/Y") ?></b></span>
                <h4 id="label_recebimentos">R$ <?php echo number_format($total_mes->val_liquido, 2, ",", ".");  ?> </h4>
              </div>
              <div class="col-6" class="recebimentos">
                <h4 class="wallet-title m-0">Recebimentos Futuros</h4> <br>
                <h4>R$ <?php echo number_format($total_futuro->val_liquido, 2, ",", ".");  ?> </h4>
              </div>
            </div>

          </div> <br>
          <!-- <p class="font-15 text-success text-center mb-4"> + $455.00 <span class="font-12 text-muted">(6.2% <i class="mdi mdi-trending-up text-success"></i>)</span></p> -->
          <ul class="nav nav-pills nav-justified" role="tablist">
            <li class="nav-item waves-effect waves-light">
              <a class="active nav-link  py-3 font-weight-semibold" data-toggle="tab" data-target="#Wallet" role="tab" aria-selected="false"><i data-feather="credit-card" class="align-self-center icon-md mr-2"></i>Operadora</a>
            </li>
            <li class="nav-item waves-effect waves-light">
              <a class=" nav-link py-3 font-weight-semibold" data-toggle="tab" data-target="#Total" role="tab" aria-selected="true"><i data-feather="home" class="align-self-center icon-md mr-2"></i>Banco</a>
            </li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane p-3" id="Total" role="tabpanel">
              <div class="row">
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
                      echo number_format($bancos->val_liquido, 2, ",", ".");
                      ?> </h4>
                    </div>

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

            <div class="tab-pane p-3 cards-selecionados" id="div_banco_selecionado" role="tab">
              <ul class="list-group wallet-bal-crypto mt-3">
                <table id="table_banco_selecionado" class="table table-selecionado">
                  <thead>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                <br>
                <ul class="nav nav-pills nav-justified" role="tablist">
                  <a type="button" id="voltar" data-target="#Total" data-toggle="tab" aria-label="Close">
                    < Voltar </a>
                  </ul>
                </ul>
              </div>
              <div class="tab-pane p-3 cards-selecionados" id="div_operadora_selecionada" role="tab">
                <ul class="list-group wallet-bal-crypto mt-3">
                  <table id="table_operadora_selecionado" class="table table-selecionado">
                    <thead>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                  <br>
                  <ul class="nav nav-pills nav-justified" role="tablist">
                    <a type="button" id="voltar_operadora" data-target="#Wallet" data-toggle="tab" aria-label="Close">
                      < Voltar </a> </ul> </ul>
                    </div>
                    <div class="tab-pane p-3 active" id="Wallet" role="tabpanel">
                      <ul id="ul_operadora" class="list-group wallet-bal-crypto mt-3">
                        @foreach($dados_operadora as $operadora)
                        <li class="list-group-item align-items-center d-flex justify-content-between">
                          <div class="col-12 row">
                            <img src="{{ $operadora->IMAGEMAD}}" class="align-self-center" style="width: 60px">
                            <div class="col-7 media-body align-self-center">
                              <!-- <div class="coin-bal row"> -->
                              <h4 class="m-0" style="text-align: right; font-size: 14px; color: #257E4A">R$ <?php
                              echo number_format($operadora->val_liquido, 2, ",", ".");
                              ?> </h4>

                            </div>
                            <!--end media body-->
                            <div class="col-1 media-body align-self-center">
                              <!-- <a style="margin-right: -60px" onclick="showTableBancoSelecionado({{$teste}})" data-toggle="tab" href="#div_banco_selecionado"><i class="thumb-lg mdi mdi-chevron-right"></i> </a> -->
                              <!-- <a data-toggle="tab" href="#div_banco_selecionado" role="tab" aria-selected="true"><i class="thumb-lg mdi mdi-chevron-right"></i></a> -->
                              <?php $ad = $operadora->CODIGO ?>

                              <a id="{{ "operadora".$operadora->CODIGO}}" data-toggle="tab" data-target="#div_operadora_selecionada" onclick="showTableOperadoraSelecionadaInicial({{$ad}})" role="tab" aria-selected="false" style="display: block"><i class="thumb-lg mdi mdi-chevron-right"></i> </a>

                            </div>
                          </div>
                        </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

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

        </div>
        @stop

        @section('footerScript')
        <script src="{{ URL::asset('plugins/moment/moment.js')}}"></script>
        <script src="{{ URL::asset('plugins/apexcharts/irregular-data-series.js')}}"></script>
        <script src="{{ URL::asset('plugins/apexcharts/ohlc.js')}}"></script>
        <script src="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>

        <script type="text/javascript">
        var param = <?php echo $dados_cliente ?>;
        var dados_dash_vendas = <?php echo $dados_dash_vendas ?>;
        var dados_dash_vendas_modalidade = <?php echo $dados_dash_vendas_modalidade ?>;
        var dados_dash_vendas_bandeira = <?php echo $dados_dash_vendas_bandeira ?>;
        </script>
        <!-- <script type="text/javascript" src="assets/js/autorizacao-cielo.js">  </script> -->
        <script type="text/javascript" src="assets/js/grafico-dash-vendas.js"> </script>

        <script>
        $(window).on("load", function() {
          const alerta_global = "<?= $frase->ALERTA_GLOBAL ?>";
          if (alerta_global) {
            $("#modal-alerta-global").modal({
              show: true
            });
          }
          preCarregarGraficoVendas();
          preCarregarGraficoVendasBandeira();
          preCarregarGraficoVendasModalidade();
          preCarregarGraficoVendasProduto();
        });

        let periodo = null;
        let grafico_vendas_operadora = null;
        let grafico_vendas_modalidade = null;
        let grafico_vendas_bandeira = null;
        let grafico_vendas_produto = null;
        let bancos_dados = null
        let operadoras_dados = null;

        function preCarregarGraficoVendas() {
          let dados_grafico = [];
          let totalQtd = 0;
          let totalBruto = 0;
          let totalTx = 0;
          let totalLiq = 0;
          let totalTicket = 0;

          const dash_vendas = <?php echo $dados_dash_vendas ?>;
          dash_vendas.forEach((dados_dash) => {
            if (dados_dash.COD_PERIODO == 2 && dados_dash.QUANTIDADE > 0) {
              dados_grafico.push(dados_dash);

              let html = "<tr>";
              html += "<td>" + "<div class='tooltip-hint' data-title='" + dados_dash.ADQUIRENTE + "'>" +
              "<img src='" + dados_dash.IMAGEM + "'/>" + "</div>" + "</td>";
              html += "<td>" + dados_dash.QUANTIDADE_REAL + "</td>";
              html += "<td>" + Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL'
              }).format(dados_dash.TOTAL_BRUTO) + "</td>";
              html += "<td>" + Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL'
              }).format(dados_dash.TOTAL_TAXA) + "</td>";
              html += "<td>" + Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL'
              }).format(dados_dash.TOTAL_LIQUIDO) + "</td>";

              html += "</tr>";

              totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
              totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
              totalTx += parseFloat(dados_dash.TOTAL_TAXA);
              totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
              totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

              $('#table_vendas_operadora').append(html);

              document.getElementById("dropdownMenuButton").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
            }
          })

          let htmlSubTotal = "<tr class='subtotal-dash'>";
          htmlSubTotal += "<td>" + "Total" + "</td>";
          htmlSubTotal += "<td>" + totalQtd + "</td>";
          htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(totalBruto) + "</td>";
          htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(totalTx) + "</td>";
          htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(totalLiq) + "</td>";
          htmlSubTotal += "</tr>";

          $('#table_vendas_operadora tfoot').append(htmlSubTotal);

          periodo = 2;
          localStorage.setItem('periodo_venda_operadora', 2);
          geraGraficoVendas(dados_grafico, 1);
        }

        function preCarregarGraficoVendasBandeira() {
          let dados_grafico = [];
          let totalQtd = 0;
          let totalBruto = 0;
          let totalTx = 0;
          let totalLiq = 0;
          let totalTicket = 0;

          const dash_vendas = <?php echo $dados_dash_vendas_bandeira ?>;

          dash_vendas.forEach((dados_dash) => {
            if (dados_dash.COD_PERIODO == 2 && dados_dash.QUANTIDADE > 0) {
              dados_grafico.push(dados_dash);

              let html = "<tr>";
              html += "<td>" + "<div class='tooltip-hint' data-title='" + dados_dash.BANDEIRA + "'>" +
              "<img style='position: static' src='" + dados_dash.IMAGEM + "'/>" + "</div>" + "</td>";
              // html += "<td>" + "<img src='" + `${dados_dash.IMAGEM || 'assets/images/iconCart.jpeg'}` + "' data-toggle='tooltip' title='" + dados_dash.BANDEIRA + "'/>" + "</td>";
              html += "<td>" + dados_dash.QUANTIDADE_REAL + "</td>";
              html += "<td>" + Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL'
              }).format(dados_dash.TOTAL_BRUTO) + "</td>";
              html += "<td>" + Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL'
              }).format(dados_dash.TOTAL_TAXA) + "</td>";
              html += "<td>" + Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL'
              }).format(dados_dash.TOTAL_LIQUIDO) + "</td>";
              html += "</tr>";

              totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
              totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
              totalTx += parseFloat(dados_dash.TOTAL_TAXA);
              totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
              totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

              $('#table_vendas_bandeira').append(html);

              document.getElementById("dropdownMenuButtonBandeira").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
            }
          })

          let htmlSubTotal = "<tr class='subtotal-dash'>";
          htmlSubTotal += "<td>" + "Total" + "</td>";
          htmlSubTotal += "<td>" + totalQtd + "</td>";
          htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(totalBruto) + "</td>";
          htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(totalTx) + "</td>";
          htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(totalLiq) + "</td>";
          htmlSubTotal += "</tr>";

          $('#table_vendas_bandeira tfoot').append(htmlSubTotal);

          periodo = 2;
          localStorage.setItem('periodo_venda_bandeira', 2);
          geraGraficoVendasBandeira(dados_grafico, 1);
        }

        function preCarregarGraficoVendasProduto() {
          let dados_grafico = [];
          let totalQtd = 0;
          let totalBruto = 0;
          let totalTx = 0;
          let totalLiq = 0;
          let totalTicket = 0;

          const dash_vendas_produto = <?php echo $dados_dash_vendas_produto ?>;

          dash_vendas_produto.forEach((dados_dash) => {
            if (dados_dash.COD_PERIODO == 2 && dados_dash.QUANTIDADE > 0) {
              dados_grafico.push(dados_dash);

              var html = "<tr>";
              html += "<td style='color: #231F20'>" + dados_dash.PRODUTO_WEB + "</td>";
              html += "<td style='color: #231F20'>" + dados_dash.QUANTIDADE_REAL + "</td>";
              html += "<td style='color: #231F20'>" + Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL'
              }).format(dados_dash.TOTAL_BRUTO) + "</td>";
              html += "<td style='color: red'>" + Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL'
              }).format(dados_dash.TOTAL_TAXA) + "</td>";
              html += "<td style='color: #231F20'>" + Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL'
              }).format(dados_dash.TOTAL_LIQUIDO) + "</td>";
              html += "</tr>";

              totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
              totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
              totalTx += parseFloat(dados_dash.TOTAL_TAXA);
              totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
              totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

              $('#table_vendas_produto').append(html);

              document.getElementById("dropdownMenuButtonProduto").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
            }
          })

          let htmlSubTotal = "<tr class='subtotal-dash'>";
          htmlSubTotal += "<td>" + "Total" + "</td>";
          htmlSubTotal += "<td>" + totalQtd + "</td>";
          htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(totalBruto) + "</td>";
          htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(totalTx) + "</td>";
          htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(totalLiq) + "</td>";
          htmlSubTotal += "</tr>";

          $('#table_vendas_produto tfoot').append(htmlSubTotal);

          periodo = 2;
          localStorage.setItem('periodo_venda_produto', 2);
          geraGraficoVendasProduto(dados_grafico, 1);
        }

        function preCarregarGraficoVendasModalidade() {
          let dados_grafico = [];
          let totalQtd = 0;
          let totalBruto = 0;
          let totalTx = 0;
          let totalLiq = 0;
          let totalTicket = 0

          const dashboard_vendas_modalidade = <?php echo $dados_dash_vendas_modalidade ?>;

          $('#table_vendas_modalidade tbody').empty();

          dashboard_vendas_modalidade.forEach((dados_dash) => {
            if (dados_dash.COD_PERIODO == 2 && dados_dash.QUANTIDADE > 0) {

              var html = "<tr>";
              html += "<td>" + dados_dash.DESCRICAO + "</td>";
              html += "<td style='color: #231F20'>" + dados_dash.QUANTIDADE_REAL + "</td>";
              html += "<td style='color: #231F20'>" + Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL'
              }).format(dados_dash.TOTAL_BRUTO) + "</td>";
              html += "<td style='color: red'>" + Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL'
              }).format(dados_dash.TOTAL_TAXA) + "</td>";
              html += "<td style='color: #231F20'>" + Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL'
              }).format(dados_dash.TOTAL_LIQUIDO) + "</td>";
              html += "</tr>";

              $('#table_vendas_modalidade').append(html);

              totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
              totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
              totalTx += parseFloat(dados_dash.TOTAL_TAXA);
              totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
              totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

              dados_grafico.push(dados_dash);
              document.getElementById("dropdownMenuButtonModalidade").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
            }
          })

          let htmlSubTotal = "<tr class='subtotal-dash'>";
          htmlSubTotal += "<td>" + "Total" + "</td>";
          htmlSubTotal += "<td>" + totalQtd + "</td>";
          htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(totalBruto) + "</td>";
          htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(totalTx) + "</td>";
          htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(totalLiq) + "</td>";
          htmlSubTotal += "</tr>";

          $('#table_vendas_modalidade tfoot').append(htmlSubTotal);

          periodo = 2;
          localStorage.setItem('periodo_venda_modalidade', 2);
          geraGraficoVendasModalidade(dados_grafico);
        }

        function trocaPeriodo(cod_periodo, tipo, label_button) {
          let dados_grafico = [];
          let totalQtd = 0;
          let totalBruto = 0;
          let totalTx = 0;
          let totalLiq = 0;
          let totalTicket = 0;

          let dash_vendas = <?php echo $dados_dash_vendas ?>;

          if (tipo == 'operadora') {
            dash_vendas = <?php echo $dados_dash_vendas ?>;

            $('#table_vendas_operadora tbody').empty();
            $('#table_vendas_operadora tfoot').empty();

            dash_vendas.forEach((dados_dash) => {
              if (dados_dash.COD_PERIODO == cod_periodo && dados_dash.QUANTIDADE > 0) {

                let html = "<tr>";
                html += "<td>" + "<img src='" + dados_dash.IMAGEM + "' style='width: 35px'/>" + "</td>";
                html += "<td style='color: #231F20'>" + dados_dash.QUANTIDADE_REAL + "</td>";
                html += "<td style='color: #231F20'>" + Intl.NumberFormat('pt-br', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(dados_dash.TOTAL_BRUTO) + "</td>";
                html += "<td style='color: red'>" + Intl.NumberFormat('pt-br', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(dados_dash.TOTAL_TAXA) + "</td>";
                html += "<td style='color: #231F20'>" + Intl.NumberFormat('pt-br', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(dados_dash.TOTAL_LIQUIDO) + "</td>";
                html += "</tr>";

                $('#table_vendas_operadora').append(html);

                totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
                totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
                totalTx += parseFloat(dados_dash.TOTAL_TAXA);
                totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
                totalTicket += parseFloat(dados_dash.TICKET_MEDIO);
                dados_grafico.push(dados_dash);
                document.getElementById("dropdownMenuButton").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
              }
            })

            let htmlSubTotal = "<tr class='subtotal-dash'>";
            htmlSubTotal += "<td>" + "Total" + "</td>";
            htmlSubTotal += "<td>" + totalQtd + "</td>";
            htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
              style: 'currency',
              currency: 'BRL'
            }).format(totalBruto) + "</td>";
            htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
              style: 'currency',
              currency: 'BRL'
            }).format(totalTx) + "</td>";
            htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
              style: 'currency',
              currency: 'BRL'
            }).format(totalLiq) + "</td>";
            htmlSubTotal += "</tr>";

            document.getElementById("dropdownMenuButton").innerHTML = label_button + ' ' + '<i class="mdi mdi-chevron-down"></i>';

            $('#table_vendas_operadora tfoot').append(htmlSubTotal);

            if (dados_grafico.length == 0) {
              grafico_vendas_operadora.destroy();
            } else {
              grafico_vendas_operadora.destroy();

              periodo = cod_periodo;
              localStorage.setItem('periodo_venda_operadora', cod_periodo);
              geraGraficoVendas(dados_grafico);
            }

          } else if (tipo == 'bandeira') {
            dash_vendas = <?php echo $dados_dash_vendas_bandeira ?>;

            $('#table_vendas_bandeira tbody').empty();
            $('#table_vendas_bandeira tfoot').empty();

            dash_vendas.forEach((dados_dash) => {
              if (dados_dash.COD_PERIODO == cod_periodo && dados_dash.QUANTIDADE > 0) {

                var html = "<tr>";
                html += "<td>" + "<img src='" + dados_dash.IMAGEM + "' style='width: 28px'/>" + "</td>";
                html += "<td style='color: #231F20'>" + dados_dash.QUANTIDADE_REAL + "</td>";
                html += "<td style='color: #231F20'>" + Intl.NumberFormat('pt-br', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(dados_dash.TOTAL_BRUTO) + "</td>";
                html += "<td style='color: red'>" + Intl.NumberFormat('pt-br', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(dados_dash.TOTAL_TAXA) + "</td>";
                html += "<td style='color: #231F20'>" + Intl.NumberFormat('pt-br', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(dados_dash.TOTAL_LIQUIDO) + "</td>";
                html += "</tr>";

                $('#table_vendas_bandeira').append(html);

                totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
                totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
                totalTx += parseFloat(dados_dash.TOTAL_TAXA);
                totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
                totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

                dados_grafico.push(dados_dash);

                document.getElementById("dropdownMenuButtonBandeira").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
              }
            })

            var htmlSubTotal = "<tr class='subtotal-dash'>";
            htmlSubTotal += "<td>" + "Total" + "</td>";
            htmlSubTotal += "<td>" + totalQtd + "</td>";
            htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
              style: 'currency',
              currency: 'BRL'
            }).format(totalBruto) + "</td>";
            htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
              style: 'currency',
              currency: 'BRL'
            }).format(totalTx) + "</td>";
            htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
              style: 'currency',
              currency: 'BRL'
            }).format(totalLiq) + "</td>";
            htmlSubTotal += "</tr>";

            document.getElementById("dropdownMenuButtonBandeira").innerHTML = label_button + ' ' + '<i class="mdi mdi-chevron-down"></i>';

            $('#table_vendas_bandeira tfoot').append(htmlSubTotal);

            if (dados_grafico.length == 0) {
              grafico_vendas_bandeira.destroy();
            } else {
              grafico_vendas_bandeira.destroy();

              periodo = cod_periodo;
              localStorage.setItem('periodo_venda_bandeira', 2);
              geraGraficoVendasBandeira(dados_grafico);
            }

          } else if (tipo == 'modalidade') {

            dash_vendas = <?php echo $dados_dash_vendas_modalidade ?>;

            $('#table_vendas_modalidade tbody').empty();
            $('#table_vendas_modalidade tfoot').empty();

            dash_vendas.forEach((dados_dash) => {
              if (dados_dash.COD_PERIODO == cod_periodo && dados_dash.QUANTIDADE > 0) {

                var html = "<tr>";
                html += "<td>" + dados_dash.DESCRICAO + "</td>";
                html += "<td>" + dados_dash.QUANTIDADE_REAL + "</td>";
                html += "<td>" + Intl.NumberFormat('pt-br', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(dados_dash.TOTAL_BRUTO) + "</td>";
                html += "<td style='color: red'>" + Intl.NumberFormat('pt-br', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(dados_dash.TOTAL_TAXA) + "</td>";
                html += "<td>" + Intl.NumberFormat('pt-br', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(dados_dash.TOTAL_LIQUIDO) + "</td>";
                html += "</tr>";

                $('#table_vendas_modalidade').append(html);

                totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
                totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
                totalTx += parseFloat(dados_dash.TOTAL_TAXA);
                totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
                totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

                dados_grafico.push(dados_dash);

                document.getElementById("dropdownMenuButtonModalidade").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
              }
            })

            var htmlSubTotal = "<tr class='subtotal-dash'>";
            htmlSubTotal += "<td>" + "Total" + "</td>";
            htmlSubTotal += "<td>" + totalQtd + "</td>";
            htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
              style: 'currency',
              currency: 'BRL'
            }).format(totalBruto) + "</td>";
            htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
              style: 'currency',
              currency: 'BRL'
            }).format(totalTx) + "</td>";
            htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
              style: 'currency',
              currency: 'BRL'
            }).format(totalLiq) + "</td>";
            htmlSubTotal += "</tr>";

            document.getElementById("dropdownMenuButtonModalidade").innerHTML = label_button + ' ' + '<i class="mdi mdi-chevron-down"></i>';

            $('#table_vendas_modalidade tfoot').append(htmlSubTotal);

            if (dados_grafico.length == 0) {
              grafico_vendas_modalidade.destroy();
            } else {
              grafico_vendas_modalidade.destroy();

              periodo = cod_periodo;
              localStorage.setItem('periodo_venda_modalidade', cod_periodo);
              geraGraficoVendasModalidade(dados_grafico);
            }

          } else if (tipo == 'produto') {
            dash_vendas = <?php echo $dados_dash_vendas_produto ?>;

            $('#table_vendas_produto tbody').empty();
            $('#table_vendas_produto tfoot').empty();

            dash_vendas.forEach((dados_dash) => {
              if (dados_dash.COD_PERIODO == cod_periodo && dados_dash.QUANTIDADE > 0) {

                var html = "<tr>";
                html += "<td>" + dados_dash.PRODUTO_WEB + "</td>";
                html += "<td>" + dados_dash.QUANTIDADE_REAL + "</td>";
                html += "<td>" + Intl.NumberFormat('pt-br', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(dados_dash.TOTAL_BRUTO) + "</td>";
                html += "<td style='color: red'>" + Intl.NumberFormat('pt-br', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(dados_dash.TOTAL_TAXA) + "</td>";
                html += "<td>" + Intl.NumberFormat('pt-br', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(dados_dash.TOTAL_LIQUIDO) + "</td>";
                html += "</tr>";

                $('#table_vendas_produto').append(html);

                totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
                totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
                totalTx += parseFloat(dados_dash.TOTAL_TAXA);
                totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
                totalTicket += parseFloat(dados_dash.TICKET_MEDIO);

                dados_grafico.push(dados_dash);

                document.getElementById("dropdownMenuButtonProduto").innerHTML = dados_dash.PERIODO + ' ' + '<i class="mdi mdi-chevron-down"></i>';
              }
            })

            var htmlSubTotal = "<tr class='subtotal-dash'>";
            htmlSubTotal += "<td>" + "Total" + "</td>";
            htmlSubTotal += "<td>" + totalQtd + "</td>";
            htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
              style: 'currency',
              currency: 'BRL'
            }).format(totalBruto) + "</td>";
            htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
              style: 'currency',
              currency: 'BRL'
            }).format(totalTx) + "</td>";
            htmlSubTotal += "<td>" + Intl.NumberFormat('pt-br', {
              style: 'currency',
              currency: 'BRL'
            }).format(totalLiq) + "</td>";
            htmlSubTotal += "</tr>";

            document.getElementById("dropdownMenuButtonProduto").innerHTML = label_button + ' ' + '<i class="mdi mdi-chevron-down"></i>';

            $('#table_vendas_produto tfoot').append(htmlSubTotal);

            if (dados_grafico.length == 0) {
              grafico_vendas_produto.destroy();
            } else {
              grafico_vendas_produto.destroy();

              periodo = cod_periodo;
              localStorage.setItem('periodo_venda_produto', cod_periodo);
              geraGraficoVendasProduto(dados_grafico);
            }

          }

        }

        function showTableBancoSelecionado(codigo) {
          $("#table_banco_selecionado tbody").empty();

          var bancos = bancos_dados;

          var result = bancos.find(banco => banco.CODIGO == codigo);

          var val_bruto = parseFloat(result.val_bruto);
          var val_liquido = parseFloat(result.val_liquido);
          var tx = parseInt(result.val_taxa);
          var t = Number(tx).toFixed(2);
          var html = "<tr>";

          html += "<td>" + "<b text-align='left'>Recebíveis Bruto:  </b>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(val_bruto) + "</td>";


          html += "</tr>";

          html += "<tr>";

          html += "<td style='color: red'>" + "<b style='color: black'>Taxas: </b> " + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(tx) + "</b>" + "</td>";


          html += "</tr>";

          html += "<tr>";

          html += "<td>" + "<b> Tarifas Extras: </b>" + "</td>";


          html += "</tr>";

          html += "<tr>";


          html += "<td>" + "<b>Valor Líquido: </b>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(val_liquido) + "</td>";

          html += "</tr>";

          html += "<tr>";


          html += "<td style='background: #BDBDBD '>" + "<b>Situação de Pagamento: " + localStorage.getItem('situacao_pgto') + "</td>";

          html += "</tr>";

          $('#table_banco_selecionado').append(html);
          document.getElementById(result.CODIGO).classList.remove('active');
          document.getElementById("voltar").classList.remove('active');
        }

        function showTableOperadoraSelecionada(codigo) {
          $("#table_operadora_selecionado tbody").empty();

          var operadoras = operadoras_dados;

          var result = operadoras.find(operadora => operadora.CODIGO == codigo);

          var val_bruto = parseFloat(result.val_bruto);
          var val_liquido = parseFloat(result.val_liquido);
          var tx = parseFloat(result.val_taxa);
          var t = Number(tx).toFixed(2);

          var html = "<tr>";

          html += "<td>" + "<b text-align='left'>Recebíveis Bruto:  </b>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(val_bruto) + "</td>";


          html += "</tr>";

          html += "<tr>";

          html += "<td style='color: red'>" + "<b style='color: black'>Taxas: </b> " + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(tx) + "</b>" + "</td>";


          html += "</tr>";

          html += "<tr>";

          html += "<td>" + "<b> Tarifas Extras: </b>" + "</td>";


          html += "</tr>";

          html += "<tr>";


          html += "<td>" + "<b>Valor Líquido: </b>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(val_liquido) + "</td>";

          html += "</tr>";

          html += "<tr>";


          html += "<td style='background: #BDBDBD '>" + "<b>Situação de Pagamento: " + localStorage.getItem('situacao_pgto') + "</td>";

          html += "</tr>";

          $('#table_operadora_selecionado').append(html);
          document.getElementById("operadora" + result.CODIGO).classList.remove('active');
          document.getElementById("voltar_operadora").classList.remove('active');
        }

        function showRecebiveis(data, title, color, jsEvent) {
          document.getElementById("voltar").click();
          document.getElementById("voltar_operadora").click();
          document.getElementById("preloader").style.display = "block";

          const data_v = new Date(data);
          const data_venda = data_v.toLocaleDateString('pt-BR', {
            timeZone: 'UTC'
          });

          if (color) {
            if (color == '#257e4a') {
              localStorage.setItem('situacao_pgto', 'Depositado');

              document.getElementById("label_recebimentos").innerHTML = title;
              document.getElementById("label_data_recebimento").innerHTML = '<b style="color: #6E6E6E">' + data_venda + '</b>';
              $("#ul_bancos li").remove();
              $("#ul_operadora li").remove();

              var url = "{{ url('detalhe-calendario') }}" + "/" + data;

              $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function(response) {
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
                    html += "<h4 style='text-align: right; font-size: 14px; color: #257E4A'>" + Intl.NumberFormat('pt-br', {
                      style: 'currency',
                      currency: 'BRL'
                    }).format(bancos.val_liquido) + "</h4>"
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
                    html += "<img src='" + bancos.IMAGEMAD + "' style='width: 60px; ;' class='align-self-center'>"
                    html += "<div class='col-7 media-body align-self-center'>"
                    html += "<h4 class='m-0' style='font-size: 14px; text-align:right; color: #257E4A'>" + Intl.NumberFormat('pt-br', {
                      style: 'currency',
                      currency: 'BRL'
                    }).format(bancos.val_liquido) + "</h4>"
                    html += "</div>"
                    html += "<div class='col-1 media-body align-self-center'>"
                    html += "<a id='operadora" + bancos.CODIGO + "' data-toggle='tab' data-target='#div_operadora_selecionada' onclick='showTableOperadoraSelecionada(" + bancos.CODIGO + ")' role='tab' aria-selected='false' style='display: block'><i class='thumb-lg mdi mdi-chevron-right'></i> </a>"
                    html += "</div>"
                    html += "</div>"
                    html += "</li>"

                    $('#ul_operadora').append(html);

                    document.getElementById("preloader").style.display = "none";

                  })

                  // })
                }
              }).fail(function() {
                alert("Erro, tente novamente!");
                document.getElementById("preloader").style.display = "none";
                return;
              });
            } else if (color == '#2D93AD') {
              localStorage.setItem('situacao_pgto', 'Previsto');

              document.getElementById("label_recebimentos").innerHTML = title;
              document.getElementById("label_data_recebimento").innerHTML = '<b style="color: #6E6E6E">' + data_venda + '</b>';
              $("#ul_bancos li").remove();
              $("#ul_operadora li").remove();

              var url = "{{ url('detalhe-calendario-prev') }}" + "/" + data;

              $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function(response) {
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
                    html += "<h4 style='text-align: right; font-size: 14px; color: #257E4A'>" + Intl.NumberFormat('pt-br', {
                      style: 'currency',
                      currency: 'BRL'
                    }).format(bancos.val_liquido) + "</h4>"
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
                    html += "<h4 class='m-0' style='font-size: 14px; text-align:right; color: #257E4A'>" + Intl.NumberFormat('pt-br', {
                      style: 'currency',
                      currency: 'BRL'
                    }).format(bancos.val_liquido) + "</h4>"
                    html += "</div>"
                    html += "<div class='col-1 media-body align-self-center'>"
                    html += "<a id='operadora" + bancos.CODIGO + "' data-toggle='tab' data-target='#div_operadora_selecionada' onclick='showTableOperadoraSelecionada(" + bancos.CODIGO + ")' role='tab' aria-selected='false' style='display: block'><i class='thumb-lg mdi mdi-chevron-right'></i> </a>"
                    html += "</div>"
                    html += "</div>"
                    html += "</li>"

                    $('#ul_operadora').append(html);

                    document.getElementById("preloader").style.display = "none";

                  })

                  // })
                }
              }).fail(function() {
                alert("Erro, tente novamente");
                document.getElementById("preloader").style.display = "none";
              });
            }
          }
        }

        function gerarPdfVendasOperadora() {
          var codigo_periodo = localStorage.getItem('periodo_venda_operadora');
          var url = "{{ url('export-vendasoperadora')}}" + "/" + codigo_periodo;

          window.location.href = url;
        }

        function gerarPdfVendasBandeira() {
          var codigo_periodo = localStorage.getItem('periodo_venda_bandeira');
          var url = "{{ url('export-vendasbandeira')}}" + "/" + codigo_periodo;

          window.location.href = url;
        }

        function gerarPdfVendasModalidade() {
          var codigo_periodo = localStorage.getItem('periodo_venda_modalidade');
          var url = "{{ url('export-vendasmodalidade')}}" + "/" + codigo_periodo;

          window.location.href = url;
        }

        function gerarPdfVendasProduto() {
          var codigo_periodo = localStorage.getItem('periodo_venda_produto');
          var url = "{{ url('export-vendasproduto')}}" + "/" + codigo_periodo;

          window.location.href = url;
        }


        function showTableBancoSelecionadoInicial(codigo) {
          $("#table_banco_selecionado tbody").empty();

          var bancos = <?php echo $dados_bancos ?>;

          var result = bancos.find(banco => banco.CODIGO == codigo);
          var val_bruto = parseFloat(result.val_bruto);
          var val_liquido = parseFloat(result.val_liquido);
          var tx = parseFloat(result.val_tx);
          var html = "<tr>";

          html += "<td>" + "<b text-align='left'>Recebíveis Bruto:  </b>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(val_bruto) + "</td>";


          html += "</tr>";

          html += "<tr>";

          html += "<td style='color: red'>" + "<b style='color: black'>Taxas: </b> " + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(tx) + "</b>" + "</td>";


          html += "</tr>";

          html += "<tr>";

          html += "<td>" + "<b> Tarifas Extras: </b>" + "</td>";


          html += "</tr>";

          html += "<tr>";


          html += "<td>" + "<b>Valor Líquido: </b>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(val_liquido) + "</td>";

          html += "</tr>";

          html += "<tr>";


          html += "<td style='background: #BDBDBD '>" + "<b>Situação de Pagamento: " + localStorage.getItem('situacao_pgto') + "</td>";

          html += "</tr>";

          $('#table_banco_selecionado').append(html);
          document.getElementById(result.CODIGO).classList.remove('active');
          document.getElementById("voltar").classList.remove('active');

        }

        function showTableOperadoraSelecionadaInicial(codigo) {
          $("#table_operadora_selecionado tbody").empty();

          const operadoras = <?php echo $dados_operadora ?>;
          const result = operadoras.find(operadora => operadora.CODIGO == codigo);
          const val_bruto = parseFloat(result.val_bruto);
          const val_liquido = parseFloat(result.val_liquido);
          const tx = parseInt(result.val_tx);

          var html = "<tr>";
          html += "<td>" + "<b text-align='left'>Recebíveis Bruto:  </b>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(val_bruto) + "</td>";
          html += "</tr>";
          html += "<tr>";
          html += "<td style='color: red'>" + "<b style='color: black'>Taxas: </b> " + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(tx) + "</b>" + "</td>";
          html += "</tr>";
          html += "<tr>";
          html += "<td>" + "<b> Tarifas Extras: </b>" + "</td>";
          html += "</tr>";
          html += "<tr>";
          html += "<td>" + "<b>Valor Líquido: </b>" + Intl.NumberFormat('pt-br', {
            style: 'currency',
            currency: 'BRL'
          }).format(val_liquido) + "</td>";
          html += "</tr>";
          html += "<tr>";
          html += "<td style='background: #BDBDBD '>" + "<b>Situação de Pagamento: " + localStorage.getItem('situacao_pgto') + "</td>";
          html += "</tr>";

          $('#table_operadora_selecionado').append(html);
          document.getElementById("operadora" + result.CODIGO).classList.remove('active');
          document.getElementById("voltar_operadora").classList.remove('active');
        }
        </script>
        @stop
