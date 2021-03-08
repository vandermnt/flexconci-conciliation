@extends('layouts.analytics-master')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet"/>
<link href="{{ URL::asset('assets/css/dashboard/dashboard.css')}}" rel="stylesheet" type="text/css" />
<script src="{{ URL::asset('assets/js/dashboard/calendario.js')}}"></script>
<!-- <link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" /> -->

<link href='lib/main.css' rel='stylesheet' />
<script src='lib/main.js'></script>

@stop

@section('content')
<div id="dashboard_styles" class="container-fluid">
  @component('analytics.component.modal-aviso-geral')
  @endcomponent
  {{-- <div class="row">
    <div class="col-lg-12 boxs">
      <div class="row">
        <div class="col-md-6 col-lg-3">
          <div class="card report-card">
            <div class="card-body body-box">
              <div class="row d-flex">
                <div class="col-12">
                  <p class="font-weight-semibold font-12">Suporte</p>
                </div>
                <div class="col-12" style="margin-top: -20px">
                  <div class="row">
                    <div class="col-12" align="center">
                      <img class="img-card-suport" src="{{ url('assets/images/suporte.png') }}">
                      <h5 class="my-3 tel">(44) 3020-0220</h5>
                    </div>
                  </div>
                  <div class="col-12" style="margin-top: -20px">
                    <h6 class="my-3 label-atendimento">Atendimento | Segunda a sexta-feira - das 08:00h às 18:00h</h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @component('analytics.component.box-aviso')
        @slot('titulo_box')
        Divergências - Conc. de vendas
        @endslot
        @slot('body_box')
        Não há divergências no momento
        @endslot
        @endcomponent
        @component('analytics.component.box-aviso')
        @slot('titulo_box')
        Divergências - Taxas
        @endslot
        @slot('body_box')
        Não há divergências no momento
        @endslot
        @endcomponent
        @component('analytics.component.box-aviso')
        @slot('titulo_box')
        Divergências - Conc. Bancária
        @endslot
        @slot('body_box')
        Não há divergências no momento
        @endslot
        @endcomponent
      </div>
    </div>
  </div> --}}

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
                    <a class="dropdown-toggle pull-right bt-vendas-op" onclick="gerarPdfVendasOperadora()" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ url('/assets/images/export.png')}}" class="img-export">
                    </a>
                  </div>
                </div>
              </div>
              <div id="apex_pie2" class="apex-charts"></div>
              <div class="table-responsive mt-4 vendasop">
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
              <h6 id="label_sem_dados_vop" style="text-align: center; display: none"> <i class="fas fa-exclamation-triangle"></i> Não existem dados para serem exibidos ou ainda não foram disponibilizados pelas operadoras. </h6>
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
                    <a class="dropdown-toggle pull-right bt-vendas-band" onclick="gerarPdfVendasBandeira()" type="button" id="dropdownMenuButtonAgrupamento" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ url('/assets/images/export.png')}}" class="img-export">
                    </a>
                  </div>
                </div>
              </div>
              <div id="apex_pie7" class="apex-charts"></div>
              <div class="table-responsive mt-4 vendasband">
                <table id="table_vendas_bandeira" class="table tableDadosDash">
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
              <h6 id="label_sem_dados_vb" style="text-align: center; display: none"> <i class="fas fa-exclamation-triangle"></i> Não existem dados para serem exibidos ou ainda não foram disponibilizados pelas operadoras. </h6>
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
                    <a class="dropdown-toggle pull-right bt-vendas-formpg" onclick="gerarPdfVendasModalidade()" type="button" id="dropdownMenuButtonAgrupamento" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ url('/assets/images/export.png')}}" class="img-export">
                    </a>
                  </div>
                </div>
              </div>
              <div id="apex_pie8" class="apex-charts"></div>
              <div class="table-responsive mt-4 vendasmod">
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
              <h6 id="label_sem_dados_vmod" style="text-align: center; display: none"> <i class="fas fa-exclamation-triangle"></i> Não existem dados para serem exibidos ou ainda não foram disponibilizados pelas operadoras. </h6>
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
                    <a class="dropdown-toggle pull-right bt-vendas-prod" onclick="gerarPdfVendasProduto()" type="button" id="dropdownMenuButtonAgrupamento" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ url('/assets/images/export.png')}}" class="img-export">
                    </a>
                  </div>
                </div>
              </div>
              <div id="apex_pie9" class="apex-charts"></div>
              <div class="table-responsive mt-4 vendasprod">
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
              <h6 id="label_sem_dados_vprod" style="text-align: center; display: none"> <i class="fas fa-exclamation-triangle"></i> Não existem dados para serem exibidos ou ainda não foram disponibilizados pelas operadoras. </h6>
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
          <div id="preloader" class="loader"></div>
          <div class="wallet-bal-usd">
            <div class="row recebimentos">
              <div class="col-6">
                <h4 class="wallet-title m-0">Recebimentos</h4>
                <span id="label_data_recebimento" class="text-muted font-12 data-recebimento">
                  <b><?php echo date("d/m/Y") ?></b>
                </span>
                <h4 id="label_recebimentos">
                  R$ <?php echo number_format($total_mes->val_liquido, 2, ",", ".");  ?>
                </h4>
              </div>
              <div class="col-6" class="recebimentos">
                <div class="tooltip-hint" data-title='São todos os recebimentos previstos do dia <?php echo date("d/m/Y", strtotime('+1 days')) ?> em diante.'>
                  <h4 class="wallet-title m-0">
                    <i class="fas fa-info-circle"></i> Recebimentos Futuros
                  </h4>
                </div>
                <br>
                <h4>R$ <?php echo number_format($total_futuro->val_liquido, 2, ",", ".");  ?> </h4>
              </div>
            </div>
          </div> <br>
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
              <ul class="list-group wallet-bal-crypto mt-3" id="ul_bancos">
                @foreach($dados_bancos as $bancos)
                <li class="list-group-item align-items-center d-flex justify-content-between">
                  <div class="col-12 row" style='text-align: center;align-items: center;justify-content: center'>
                    <div class="col-4 tooltip-hint" data-title="{{ $bancos->BANCO_NOME }}">
                      <img src="{{ $bancos->IMAGEM}}" class="align-self-center img-bancos-detalhamento">
                    </div>
                    <div class="col-4 media-body align-self-center">
                      <h4 class="label-banco-detalhamento">
                        AG: {{ $bancos->AGENCIA}} - C/C: {{ $bancos->CONTA }}
                      </h4>
                    </div>
                    <div class="col-4 media-body align-self-center">
                      <h4 class="label-val-liquido">
                        R$ <?php echo number_format($bancos->val_liquido, 2, ",", ".");?>
                      </h4>
                    </div>
                    <div class="col-1 media-body align-self-center">
                      <a id="{{$bancos->CODIGO}}" data-toggle="tab" data-target="#div_banco_selecionado" onclick="showTableBancoSelecionado({{$bancos->CODIGO}})" role="tab" aria-selected="false">
                        <i class="thumb-lg mdi mdi-chevron-right"></i>
                      </a>
                    </div>
                  </div>
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
                      < Voltar </a>
                    </ul>
                  </ul>
                </div>
                <div class="tab-pane p-3 active" id="Wallet" role="tabpanel">
                  <ul id="ul_operadora" class="list-group wallet-bal-crypto mt-3">
                    @foreach($dados_operadora as $operadora)
                    <li class="list-group-item align-items-center d-flex justify-content-between">
                      <div class="col-12 row" style='text-align: center;align-items: center;justify-content: center'>
                        <div class="col-2 tooltip-hint" data-title="{{ $operadora->NOME_AD }}">
                          <img src="{{ $operadora->IMAGEMAD}}" class="align-self-center">
                        </div>
                        <div class="col-7 media-body align-self-center">
                          <h4 class="m-0 label-val-liquido">
                            R$ <?php echo number_format($operadora->val_liquido, 2, ",", ".");?>
                          </h4>
                        </div>
                        <div class="col-1 media-body align-self-center">
                          <a id="{{ "operadora".$operadora->CODIGO}}" data-toggle="tab" data-target="#div_operadora_selecionada" onclick="showTableOperadoraSelecionada({{$operadora->CODIGO}})" role="tab" aria-selected="false" style="display: block"><i class="thumb-lg mdi mdi-chevron-right"></i> </a>
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
      @component('analytics.component.modal-credenciamento-success')
      @endcomponent
      @component('analytics.component.modal-credenciamento-error')
      @endcomponent
    </div>
    @stop

    @section('footerScript')
    <script src="{{ URL::asset('plugins/apexcharts/irregular-data-series.js')}}"></script>
    <script src="{{ URL::asset('plugins/apexcharts/ohlc.js')}}"></script>
    <!-- <script src="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script> -->
    <script src="{{ URL::asset('assets/js/dashboard/export-pdf.js')}}"></script>
    <script src="{{ URL::asset('assets/js/dashboard/tabelas.js')}}"></script>
    <script src="{{ URL::asset('assets/js/dashboard/graficos.js')}}"></script>
    <script src="{{ URL::asset('assets/js/dashboard/formata-valores.js')}}"></script>
    <script type="text/javascript" src="assets/js/grafico-dash-vendas.js"> </script>

    <script>
    $(window).on("load", function() {
      const alerta_global = "<?= $frase->ALERTA_GLOBAL ?>";
      if (alerta_global) {
        $("#modal-alerta-global").modal({
          show: true
        });
      }

      const grafico_vendas_operadora = <?php echo $dados_dash_vendas ?>;
      const grafico_vendas_bandeira = <?php echo $dados_dash_vendas_bandeira ?>;
      const grafico_vendas_produto =  <?php echo $dados_dash_vendas_produto ?>;
      const grafico_vendas_modalidade = <?php echo $dados_dash_vendas_modalidade ?>;

      preCarregarGraficoVendas(grafico_vendas_operadora);
      preCarregarGraficoVendasBandeira(grafico_vendas_bandeira);
      preCarregarGraficoVendasModalidade(grafico_vendas_modalidade);
      preCarregarGraficoVendasProduto(grafico_vendas_produto);
    });

    let bancos_dados = <?php echo $dados_bancos ?>;
    let operadoras_dados = <?php echo $dados_operadora ?>;
    let pagamentos_antecipados = null;
    let pagamentos_normais = null;
    let pagamentos_antecipados_bancos = null;
    let pagamentos_normais_bancos = null;

    function showRecebiveis(data, title, color, jsEvent) {
      document.getElementById("voltar").click();
      document.getElementById("voltar_operadora").click();
      document.getElementById("preloader").style.display = "block";

      const data_v = new Date(data);
      const data_venda = formataData(data_v)

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

              pagamentos_normais = response[2];
              pagamentos_antecipados = response[3];

              pagamentos_normais_bancos = response[4];
              pagamentos_antecipados_bancos = response[5];
              // $('#ul_bancos').empty();
              response[0].forEach((bancos) => {
                var html = "<li class='list-group-item align-items-center d-flex justify-content-between'>"

                html += "<div class='col-12 row align-self-center' style='text-align: center;align-items: center;justify-content: center'>"
                html += "<div class='col-4' style='margin: 0'>"
                html += "<div class='tooltip-hint' data-title='"+ bancos.BANCO_NOME + "'><img src='" + bancos.IMAGEM + "' class='align-self-center img-bancos-detalhamento'></div>"
                html += "</div>"
                html += "<div class='col-4 media-body align-self-center'>"
                html += "<h4 style='font-size: 13px; margin-left: -30px'>" + "AG: " + bancos.AGENCIA + "- C/C: " + bancos.CONTA + "</h4>"
                html += "</div>"
                html += "<div class='col-4 media-body align-self-center' style='margin: 0'>"
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

                html += "<div class='col-12 row' style='text-align: center;align-items: center;justify-content: center'>"
                html += "<div class='tooltip-hint' data-title='"+ bancos.NOME_AD + "'>" + "<img src='" + bancos.IMAGEMAD + "' style='width: 60px; ;' class='align-self-center'></div>"
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

              pagamentos_normais = response[2];
              pagamentos_antecipados = response[3];

              pagamentos_normais_bancos = response[4];
              pagamentos_antecipados_bancos = response[5];
              // $('#ul_bancos').empty();


              response[0].forEach((bancos) => {
                var html = "<li class='list-group-item align-items-center d-flex justify-content-between'>"

                html += "<div class='col-12 row' style='text-align: center;align-items: center;justify-content: center'>"
                html += "<div class='col-4' style='margin: 0'>"
                html += "<img src='" + bancos.IMAGEM + "' class='align-self-center img-bancos-detalhamento'>"
                html += "</div>"
                html += "<div class='col-4 media-body align-self-center'>"
                html += "<h4 style='font-size: 13px; margin-left: -30px'>" + "AG: " + bancos.AGENCIA + "- C/C: " + bancos.CONTA + "</h4>"
                html += "</div>"
                html += "<div class='col-4 media-body align-self-center' style='margin: 0'>"
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

                html += "<div class='col-12 row' style='text-align: center;align-items: center;justify-content: center'>"
                html += "<img src='" + bancos.IMAGEMAD + "' class='align-self-center' style='width: 70px;'>"
                html += "<div class='col-7 media-body align-self-center'>"
                html += "<h4 class='m-0' style='font-size: 14px; text-align:right; color: #257E4A'>" + formataMoeda(bancos.val_liquido) + "</h4>"
                html += "</div>"
                html += "<div class='col-1 media-body align-self-center'>"
                html += "<a id='operadora" + bancos.CODIGO + "' data-toggle='tab' data-target='#div_operadora_selecionada' onclick='showTableOperadoraSelecionada(" + bancos.CODIGO + ")' role='tab' aria-selected='false' style='display: block'><i class='thumb-lg mdi mdi-chevron-right'></i> </a>"
                html += "</div>"
                html += "</div>"
                html += "</li>"

                $('#ul_operadora').append(html);

                document.getElementById("preloader").style.display = "none";

              })
            }
          }).fail(function() {
            alert("Erro, tente novamente");
            document.getElementById("preloader").style.display = "none";
          });
        }
      }
    }

    function showTableBancoSelecionado(codigo) {
      $("#table_banco_selecionado tbody").empty();

      const result = bancos_dados.find(banco => banco.CODIGO == codigo);
      const pagamento_normal = buscaPagamentoNormal(pagamentos_normais_bancos, codigo);
      const pagamento_antecipado = buscaPagamentoAntecipado(pagamentos_antecipados_bancos, codigo);
      const val_bruto = parseFloat(result.val_bruto);
      const val_liquido = parseFloat(result.val_liquido);

      geraTabelaDetalhamentoCalendario("#table_banco_selecionado tbody", val_bruto, val_liquido, result.val_taxa, pagamento_normal, pagamento_antecipado);

      document.getElementById(result.CODIGO).classList.remove('active');
      document.getElementById("voltar").classList.remove('active');
    }

    function showTableOperadoraSelecionada(codigo) {
      $("#table_operadora_selecionado tbody").empty();

      const result = operadoras_dados.find(operadora => operadora.CODIGO == codigo);
      const pagamento_normal = buscaPagamentoNormal(pagamentos_normais, codigo);
      const pagamento_antecipado = buscaPagamentoAntecipado(pagamentos_antecipados, codigo);
      const val_bruto = parseFloat(result.val_bruto);
      const val_liquido = parseFloat(result.val_liquido);

      geraTabelaDetalhamentoCalendario("#table_operadora_selecionado tbody", val_bruto, val_liquido, result.val_taxa, pagamento_normal, pagamento_antecipado);

      document.getElementById("operadora" + result.CODIGO).classList.remove('active');
      document.getElementById("voltar_operadora").classList.remove('active');
    }

    function buscaPagamentoNormal(pagamentos, codigo) {
      pagamento_normal = pagamentos ? pagamento.find(pagamento => pagamento.CODIGO == codigo) : null;
      return pagamento_normal ? pagamento_normal.tipo_pgto_antecipado : 0;
    }

    function buscaPagamentoAntecipado(pagamentos, codigo) {
      pagamento_antecipado = pagamentos ? pagamentos.find(pagamento => pagamento.CODIGO == codigo) : null;
      return pagamento_antecipado ? pagamento_antecipado.tipo_pgto_antecipado : 0;
    }

    </script>


    @stop
