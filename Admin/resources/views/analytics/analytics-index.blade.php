@extends('layouts.analytics-master')

@section('title', 'Metrica - Admin & Dashboard Template')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>
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
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th>Tipo</th>
                      <th>Quant.</th>
                      <th>Val. Bruto</th>
                      <th>Taxa</th>
                      <th>Val. Líquido</th>
                      <th>Tick. Médio</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- @foreach($dados_dash_vendas as $dados_vendas) -->
                    <tr>
                      <td id="tipo"> </td>
                      <td id="quantidade"> </td>
                      <td id="venda_total_bruto"></td>
                      <td id="venda_total_taxa"></td>
                      <td id="venda_total_liquido"></td>
                      <td id="venda_ticket_medio"></td>
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
            <h1 class="header-title mt-0" style="text-align: center">Dashboard Rececimentos Operadoras</h1>
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
  <div class="col-md-6 col-lg-3">
    <div class="card report-card">
      <div class="card-body">
        <div class="row d-flex justify-content-center">
          <div class="col-8">
            <p class="text-dark font-weight-semibold font-14">Sessions</p>
            <h3 class="my-3">24k</h3>
            <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p>
          </div>
          <div class="col-4 align-self-center">
            <div class="report-main-icon bg-light-alt">
              <i data-feather="users" class="align-self-center icon-dual-pink icon-lg"></i>
            </div>
          </div>
        </div>
      </div><!--end card-body-->
    </div><!--end card-->
  </div> <!--end col-->
  <div class="col-md-6 col-lg-3">
    <div class="card report-card">
      <div class="card-body">
        <div class="row d-flex justify-content-center">
          <div class="col-8">
            <p class="text-dark font-weight-semibold font-14">Avg.Sessions</p>
            <h3 class="my-3">00:18</h3>
            <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>1.5%</span> Weekly Avg.Sessions</p>
          </div>
          <div class="col-4 align-self-center">
            <div class="report-main-icon bg-light-alt">
              <i data-feather="clock" class="align-self-center icon-dual-secondary icon-lg"></i>
            </div>
          </div>
        </div>
      </div><!--end card-body-->
    </div><!--end card-->
  </div> <!--end col-->
  <div class="col-md-6 col-lg-3">
    <div class="card report-card">
      <div class="card-body">
        <div class="row d-flex justify-content-center">
          <div class="col-8">
            <p class="text-dark font-weight-semibold font-14">Bounce Rate</p>
            <h3 class="my-3">$2400</h3>
            <p class="mb-0 text-truncate"><span class="text-danger"><i class="mdi mdi-trending-down"></i>35%</span> Bounce Rate Weekly</p>
          </div>
          <div class="col-4 align-self-center">
            <div class="report-main-icon bg-light-alt">
              <i data-feather="pie-chart" class="align-self-center icon-dual-purple icon-lg"></i>
            </div>
          </div>
        </div>
      </div><!--end card-body-->
    </div><!--end card-->
  </div> <!--end col-->
  <div class="col-md-6 col-lg-3">
    <div class="card report-card">
      <div class="card-body">
        <div class="row d-flex justify-content-center">
          <div class="col-8">
            <p class="text-dark font-weight-semibold font-14">Goal Completions</p>
            <h3 class="my-3">85000</h3>
            <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>10.5%</span> Completions Weekly</p>
          </div>
          <div class="col-4 align-self-center">
            <div class="report-main-icon bg-light-alt">
              <i data-feather="briefcase" class="align-self-center icon-dual-warning icon-lg"></i>
            </div>
          </div>
        </div>
      </div><!--end card-body-->
    </div><!--end card-->
  </div> <!--end col-->
</div><!--end row-->

<div class="row">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title mt-0 mb-3">Browser Used By Users</h4>
        <div class="table-responsive browser_users">
          <table class="table mb-0">
            <thead class="thead-light">
              <tr>
                <th class="border-top-0">Browser</th>
                <th class="border-top-0">Sessions</th>
                <th class="border-top-0">Bounce Rate</th>
                <th class="border-top-0">Transactions</th>
              </tr><!--end tr-->
            </thead>
            <tbody>
              <tr>
                <td><i class="fab fa-chrome mr-2 text-danger font-16"></i>Chrome</td>
                <td>10853<small class="text-muted">(52%)</small></td>
                <td> 52.80%</td>
                <td>566<small class="text-muted">(92%)</small></td>
              </tr><!--end tr-->
              <tr>
                <td><i class="fab fa-safari mr-2 text-info font-16"></i>Safari</td>
                <td>2545<small class="text-muted">(47%)</small></td>
                <td> 47.54%</td>
                <td>498<small class="text-muted">(81%)</small></td>
              </tr><!--end tr-->
              <tr>
                <td><i class="fab fa-internet-explorer mr-2 text-warning font-16"></i>Internet-Explorer</td>
                <td>1836<small class="text-muted">(38%)</small></td>
                <td> 41.12%</td>
                <td>455<small class="text-muted">(74%)</small></td>
              </tr><!--end tr-->
              <tr>
                <td><i class="fab fa-opera mr-2 text-danger font-16"></i>Opera</td>
                <td>1958<small class="text-muted">(31%)</small></td>
                <td> 36.82%</td>
                <td>361<small class="text-muted">(61%)</small></td>
              </tr><!--end tr-->
              <tr>
                <td><i class="fab fa-firefox mr-2 text-blue font-16"></i>Firefox</td>
                <td>1566<small class="text-muted">(26%)</small></td>
                <td> 29.33%</td>
                <td>299<small class="text-muted">(49%)</small></td>
              </tr><!--end tr-->
            </tbody>
          </table> <!--end table-->
        </div><!--end /div-->
      </div><!--end card-body-->
    </div><!--end card-->
  </div><!--end col-->

  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title mt-0 mb-3">Traffic Sources</h4>
        <div class="table-responsive browser_users">
          <table class="table mb-0">
            <thead class="thead-light">
              <tr>
                <th class="border-top-0">Channel</th>
                <th class="border-top-0">Sessions</th>
                <th class="border-top-0">Prev.Period</th>
                <th class="border-top-0">% Change</th>
              </tr><!--end tr-->
            </thead>
            <tbody>
              <tr>
                <td><a href="" class="text-primary">Organic search</a></td>
                <td>10853<small class="text-muted">(52%)</small></td>
                <td>566<small class="text-muted">(92%)</small></td>
                <td> 52.80% <i class="fas fa-caret-up text-success font-16"></i></td>
              </tr><!--end tr-->
              <tr>
                <td><a href="" class="text-primary">Direct</a></td>
                <td>2545<small class="text-muted">(47%)</small></td>
                <td>498<small class="text-muted">(81%)</small></td>
                <td> -17.20% <i class="fas fa-caret-down text-danger font-16"></i></td>

              </tr><!--end tr-->
              <tr>
                <td><a href="" class="text-primary">Referal</a></td>
                <td>1836<small class="text-muted">(38%)</small></td>
                <td>455<small class="text-muted">(74%)</small></td>
                <td> 41.12% <i class="fas fa-caret-up text-success font-16"></i></td>

              </tr><!--end tr-->
              <tr>
                <td><a href="" class="text-primary">Email</a></td>
                <td>1958<small class="text-muted">(31%)</small></td>
                <td>361<small class="text-muted">(61%)</small></td>
                <td> -8.24% <i class="fas fa-caret-down text-danger font-16"></i></td>
              </tr><!--end tr-->
              <tr>
                <td><a href="" class="text-primary">Social</a></td>
                <td>1566<small class="text-muted">(26%)</small></td>
                <td>299<small class="text-muted">(49%)</small></td>
                <td> 29.33% <i class="fas fa-caret-up text-success"></i></td>
              </tr><!--end tr-->
            </tbody>
          </table> <!--end table-->
        </div><!--end /div-->
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
<script type="text/javascript" src="assets/js/autorizacao-cielo2.js">  </script>
<script type="text/javascript" src="assets/js/grafico-dash-vendas.js">  </script>

<script>

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

      document.getElementById("venda_total_bruto").innerHTML = total_bruto;
      document.getElementById("venda_total_taxa").innerHTML = total_taxa;
      document.getElementById("venda_total_liquido").innerHTML = total_liquido ;
      document.getElementById("venda_ticket_medio").innerHTML = total_ticket_medio;
      document.getElementById("quantidade").innerHTML = dados_dash.QUANTIDADE;
      document.getElementById("tipo").innerHTML = "Operadora";

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

  grupo = document.getElementById("tipo").innerHTML;

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
