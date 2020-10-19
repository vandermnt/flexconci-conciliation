@extends('layouts.analytics-master')

@section('title', 'Metrica - Admin & Dashboard Template')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.4.4/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>


<link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />


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
      @slot('title') Conciliação Automática de Vendas @endslot
      @slot('item1') Conciliação @endslot
      <!-- @slot('item2') Antecipação de Venda @endslot -->
      @endcomponent

    </div>
  </div>
  <form id="myform" action="{{ action('ConciliacaoAutomaticaVendasController@conciliarManualmente')}}" method="post">
    <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body" >
            <div class="row">
              <div class="col-sm-6">
                <div id="filtrodata">
                  <div class="form-group">
                    <div class="row">

                      <div class="col-sm-6">
                        <h6 style="color: #424242; font-size: 11.5px"> Data Inicial: </h6>
                        <input style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" type="date" id="date_inicial" value="{{  date("Y-m-01")}}" name="data_inicial" max="3000-12-31">
                      </div>
                      <div class="col-sm-6">
                        <h6 style="color: #424242; font-size: 11.5px"> Data Final: </h6>
                        <input style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" type="date" id="date_final" value="{{ date("Y-m-d") }}" name="data_final">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row" style="margin-top: -10px">

              <div class="col-sm-6" style="margin-top: -10px">
                <div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-12">
                        <h6 style="color: #424242; font-size: 11.5px"> Empresa: </h6>
                        <input id="adquirente" style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" name="adquirente">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-2">
                <button id="buttonpesquisar" type="button" class="btn btn-sm" data-toggle="modal" data-target="#staticBackdrop" style="margin-top: 15px; width: 100%">
                  <b>Selecionar</b>
                </button>
              </div>

            </div>

            <div class="row" style="margin-top: -16px">
              <div class="col-sm-12">
                <h6 style="color: #424242; font-size:12px"> Status Conciliação: </h6>
                <div class="row">

                  <div class="row" style="">
                    @foreach($status_conciliacao as $status)
                    <div style="margin-top: -10px; margin-left: 25px">
                      <input type="checkbox" checked  value="{{ $status->CODIGO }}" name="status_conciliacao[]" id="{{ "statusFinan".$status->CODIGO }}"required>
                      <label style="font-size: 12px; color: #424242; margin-top: 5px"  for="{{ "statusFinan".$status->CODIGO }}">{{ $status->STATUS_CONCILIACAO}}</label>
                    </div>
                    @endforeach
                  </div>

                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div id="btfiltro" style="margin-top: -4px; display:block; text-align: right">
                  <a id="" onclick="limparFiltros()" style="align-items: right; background: white; color: #2D5275; border-color: #2D5275" class="btn btn-sm"> <i class="far fa-trash-alt"></i> <b>Limpar Campos</b>  </a>

                  <a id="submitFormLogin" style="align-items: right; background: white; color: #2D5275; border-color: #2D5275" class="btn btn-sm"> <i class="fas fa-search"></i> <b>Pesquisar</b>  </a>
                  <!-- <a id="submitFormLogin" class="btn btn-gradient-primary btn-round btn-block" style="background: #2D5275; color: white" type="button">Log In <i class="fas fa-sign-in-alt ml-1"></i></a> -->
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog" style="max-width: 400px;">
            <div class="modal-content">
              <div class="modal-header" style="background: #2D5275;">
                <h5 class="modal-title" id="staticBackdropLabel" style="color: white">Empresas</h5>
                <button style="color: white" type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-sm-12">
                    <h6> Pesquisar </h6>
                  </div>
                  <div class="col-sm-12">
                    <input id="ft" style="margin-top: -6px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px" class="form-control" name="valor_venda" onKeyDown="filtroCnpj({{$grupos_clientes}})">
                  </div>

                </div> <br>

                <div class="row">
                  <div class="col-sm-7">
                    <p><b>EMPRESA</b></p>
                  </div>
                  <div class="col-sm-4">
                    <p><b>CNPJ</b></p>
                  </div>
                  <div class="col-sm-1">
                    <input id="allCheck" onchange="allCheckbox({{$grupos_clientes}})" type="checkbox">
                  </div>
                  @if(isset($grupos_clientes))
                  @foreach($grupos_clientes as $cliente)

                  <div id="{{ $cliente->NOME_EMPRESA }}" style="display:block" class="col-sm-7">
                    <p>{{ $cliente->NOME_EMPRESA }}</p>
                  </div>
                  <div id="{{ $cliente->CNPJ }}" style="display:block" class="col-sm-4">
                    <p>{{ $cliente->CNPJ }}</p>
                  </div>
                  <div id="{{ "divCod".$cliente->CODIGO }}" style="display:block" class="col-sm-1">
                    <input id="{{ $cliente->CODIGO }}" value="{{ $cliente->CNPJ }}" name="array[]" type="checkbox">
                  </div>
                  <hr>
                  @endforeach
                  @endif
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="addSelecionados({{$grupos_clientes}})"><b>Confirmar</b></button>
              </div>
            </div>
          </div>
        </div>

      </form>


      <div id="resultadosPesquisa" style="display: none">

        <div class="row">
          <div class="col-md-6 col-lg-3">
            <div class="card report-card">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-8">
                    <p class="text-dark font-weight-semibold font-12">VENDAS ERP</p>
                    <h4 id="total_registros" class="my-3">0</h4>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img style="width: 40px" src="{{ url('assets/images/conciliacao/vendaserp.png')}}" alt="">
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
                    <p class="text-dark font-weight-semibold font-12">CONCILIADO</p>
                    <h4 id="total_bruto_vendas" class="my-3">0</h4>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img style="width: 40px" src="{{ url('assets/images/conciliacao/conciliado.png')}}" alt="">
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
                    <p class="text-dark font-weight-semibold font-12">DIVERGENTE</p>
                    <h4 id="total_taxa_cobrada" class="my-3">0</h4>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img style="width: 40px" src="{{ url('assets/images/conciliacao/conciliadodiv.png')}}" alt="">
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
                    <p class="text-dark font-weight-semibold font-12">CONCILIADO MANUALMENTE</p>
                    <h4 id="total_liquido_vendas" class="my-3">0</h4>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img style="width: 40px" src="{{ url('assets/images/conciliacao/conciliadomanualmente.png')}}" alt="">
                    </div>
                  </div>
                </div>
              </div><!--end card-body-->
            </div><!--end card-->
          </div> <!--end col-->
          <div class="col-md-6 col-lg-4">
            <div class="card report-card">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-8">
                    <p class="text-dark font-weight-semibold font-12">JUSTIFICADO</p>
                    <h4 id="total_liquido_vendas" class="my-3">0</h4>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img style="width: 40px" src="{{ url('assets/images/conciliacao/justificado.png')}}" alt="">
                    </div>
                  </div>
                </div>
              </div><!--end card-body-->
            </div><!--end card-->
          </div> <!--end col-->
          <div class="col-md-6 col-lg-4">
            <div class="card report-card">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-8">
                    <p class="text-dark font-weight-semibold font-12">VENDAS ERP NÃO CONCILIADAS</p>
                    <h4 id="total_liquido_vendas" class="my-3">0</h4>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img style="width: 40px" src="{{ url('assets/images/conciliacao/vendaserpnotconc.png')}}" alt="">
                    </div>
                  </div>
                </div>
              </div><!--end card-body-->
            </div><!--end card-->
          </div> <!--end col-->
          <div class="col-md-6 col-lg-4">
            <div class="card report-card">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-8">
                    <p class="text-dark font-weight-semibold font-12">VENDAS OPERADORA NÃO CONCILIADAS</p>
                    <h4 id="total_liquido_vendas" class="my-3">0</h4>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img style="width: 40px" src="{{ url('assets/images/conciliacao/vendasoperadoranotconc.png')}}" alt="">
                    </div>
                  </div>
                </div>
              </div><!--end card-body-->
            </div><!--end card-->
          </div> <!--end col-->

        </div>

        <h4> Vendas </h4>
        <div style="overflow: scroll; font-size: 13px; overflow-x: scroll; height: 270px">

          <table id="jsgrid-table" class="table " style="white-space: nowrap;">

            <thead>
              <tr>
                <th>  </th>
                <th> Empresa </th>
                <th> CNPJ  </th>
                <th> Operador </th>
                <th> Dt.Venda </th>
                <th> Dt.Prevista </th>
                <th> Bandeir </th>
                <th> Forma de Pagament </th>
                <th> NSU</th>
                <th> Autorização</th>
                <th> Cartã </th>
                <th> Valor Bruto</th>
                <th> Taxa % </th>
                <th> Taxa R$</th>
                <th> Outras Tarifa </th>
                <th> Valor Líquido </th>
                <th> Parcela</th>
                <th> Total Parc.</th>
                <th> Hora</th>
                <th> Estabeleciment </th>
                <th> Banc </th>
                <th> Agênci </th>
                <th> Conta</th>
                <th> Observação <input style="min-width: 135px; margin: 0"></th>
                <th> Produt </th>
                <th> Meio de Captur </th>
              </tr>
            </thead>
            <tbody>

            </tbody>

          </table>


        </div>

      </div>

      <div id="resultadosPesquisaErp" style="display: none">

        <br>

        <h4> Vendas ERP</h4>

        <div style="overflow: scroll; font-size: 13px; overflow-x: scroll; max-height: 270px">
          <table id="jsgrid-table-erp" class="table " style="white-space: nowrap; background:white; color: #2D5275">

            <thead>
              <tr style="background: #2D5275;">
                <th>  </th>
                <th> DATA VENDA  </th>
                <th> PREVIS. PGT  </th>
                <th> NSU  </th>
                <th> TOTAL VENDA </th>
                <th> Nº PARCELA </th>
                <th> TOTAL PARCELA </th>
                <th> LIQ. PARCELA </th>
                <th> DESCRIÇÃO ERP </th>
                <th> COD. AUTORIZAÇÃO </th>
                <th> ID. VENDA CLIENTE  </th>
                <th> MEIO DE CAPTURA  </th>
              </tr>
              <tbody>
              </tbody>
            </table>
          </div>
          <br>
          <button type="button" onclick="conciliar()" style="background: #2D5275; box-shadow: none" class="btn btn-primary btn-lg btn-block"><b>Conciliar</b></button>
        </div>
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

$(function(){
  $("#jsgrid-table input").keyup(function(){
    var index = $(this).parent().index();
    var nth = "#jsgrid-table td:nth-child("+(index+1).toString()+")";
    var valor = $(this).val().toUpperCase();
    $("#jsgrid-table tbody tr").show();
    $(nth).each(function(){
      if($(this).text().toUpperCase().indexOf(valor) < 0){
        $(this).parent().hide();
      }
    });
  });

  $("#jsgrid-table input").blur(function(){
    $(this).val("");
  });
});

$('#submitFormLogin').click(function(){

  $('#jsgrid-table tbody').empty();

  arrayModalidade = [];
  arrayBandeira = [];
  arrayStatusConciliacao = [];

  data_inicial = document.getElementById("date_inicial").value;
  data_final = document.getElementById("date_final").value;

  modalidades = <?php echo $modalidades ?>;
  bandeiras = <?php echo $bandeiras ?>;
  status_conciliacao = <?php echo $status_conciliacao ?>;

  modalidades.forEach((modalidade) => {
    if(document.getElementById("inputMod"+modalidade.CODIGO).checked){
      arrayModalidade.push(modalidade.CODIGO);
    }
  });

  bandeiras.forEach((bandeira) => {
    if(document.getElementById(bandeira.CODIGO).checked){
      arrayBandeira.push(bandeira.CODIGO);
    }
  });

  document.getElementById("preloader").style.display = "block";

  $.ajax({
    url: "{{ url('conciliacao-manual') }}",
    type: "post",
    header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: ({_token: '{{csrf_token()}}' , data_inicial, data_final, arrayBandeira, arrayModalidade, arrayStatusConciliacao}),
    dataType: 'json',
    success: function (response){

      var jsonVendas = JSON.stringify(response[0]);
      var jsonVendasERP = JSON.stringify(response[1]);

      if(response){
        for(var i=0;i< response[0].length; i++){
          var data_v = new Date(response[0][i].DATA_VENDA);
          var data_venda = data_v.toLocaleDateString('pt-BR', {timeZone: 'UTC'});

          var data_p = new Date(response[0][i].DATA_PREVISTA_PAGTO);
          var data_prev_pag = data_p.toLocaleDateString('pt-BR', {timeZone: 'UTC'});

          const number = response[0][i].VALOR_BRUTO;

          const formatter = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
          });

          const formatted = formatter.format(number);

          const val_liq = response[0][i].VALOR_LIQUIDO;
          const formatterliq = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
          });
          const formatted_liq = formatterliq.format(val_liq);

          const val_tx = response[0][i].VALOR_TAXA;
          const formattertx = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
          });
          const formatted_tx = formattertx.format(val_tx);

          const outras_despesas = response[0][i].OUTRAS_DESPESAS;
          const outras = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
          });
          const outras_despesas_format = outras.format(outras_despesas);

          var cod = response[0][i].COD;

          //tira 2 casas decimais da taxa
          var a = response[0][i].PERCENTUAL_TAXA;
          var val_taxa = Number(a).toFixed(2);
          var html = "<tr>";

          // setTimeout(function () {
          html +="<td>"+"<input type='checkbox'"+"</td>";

          html +="<td>"+response[0][i].EMPRESA+"</td>";
          html +="<td>"+response[0][i].CNPJ+"</td>";

          // html += "<td>"+"<img src='"+dados_dash.IMAGEM+"' id='cartao'/>"+"</td>";
          html +="<td>"+"<img src='"+response[0][i].IMAGEMAD+"' style='width: 60px'/>"+"</td>";
          html +="<td>"+data_venda+"</td>";
          html +="<td>"+data_prev_pag+"</td>";
          if(response[0][i].IMAGEMBAD == null){
            html +="<td>"+"<img src='assets/images/iconCart.jpeg' style='width: 40px'/>"+"</td>";
          }else{
            html +="<td>"+"<img src='"+response[0][i].IMAGEMBAD+"' style='width: 40px'/>"+"</td>";
          }
          html +="<td>"+response[0][i].DESCRICAO+"</td>";
          html +="<td>"+response[0][i].NSU+"</td>";
          html +="<td>"+response[0][i].AUTORIZACAO+"</td>";
          html +="<td>"+response[0][i].CARTAO+"</td>";
          html +="<td>"+formatted +"</td>";
          html +="<td>"+val_taxa+"</td>";
          html +="<td>"+formatted_tx+"</td>";
          html +="<td>"+outras_despesas_format+"</td>";
          html +="<td>"+formatted_liq+"</td>";
          html +="<td>"+response[0][i].PARCELA+"</td>";
          html +="<td>"+response[0][i].TOTAL_PARCELAS+"</td>";
          html +="<td>"+response[0][i].HORA_TRANSACAO+"</td>";
          html +="<td>"+response[0][i].ESTABELECIMENTO+"</td>";
          html +="<td>"+response[0][i].BANCO+"</td>";
          html +="<td>"+response[0][i].AGENCIA+"</td>";
          html +="<td>"+response[0][i].CONTA+"</td>";
          html +="<td>"+response[0][i].OBSERVACOES+"</td>";
          if(response[0][i].COD_PRODUTO !=  null){
            html +="<td>"+response[0][i].PRODUTO_WEB+"</td>";
          }else{
            html +="<td>"+""+"</td>";
          }
          html +="<td>"+response[0][i].MEIOCAPTURA+"</td>";

          // var url = "{{ url('/impressao-vendas')}}"+"/"+response[0][i].COD;
          var url = "#";

          html +="</tr>";
          $('#jsgrid-table').append(html);
          // },100);
        }

        var htmll = "<tr id='rodapeTable'>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+response[2]+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+response[4]+"</td>";
        htmll +="<td>"+response[6]+"</td>";
        htmll +="<td>"+response[1]+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";

        htmll +="</tr>";
        $('#jsgrid-table').append(htmll);
        document.getElementById("resultadosPesquisa").style.display = "block";

        // var data_v = new Date(response[1][i].DATA_VENDA);
        // var data_venda = data_v.toLocaleDateString();
        for(var i=0;i< response[1].length; i++){

          var data_p = new Date(response[1][i].DATA_VENCIMENTO);
          var data_prev_pag = data_p.toLocaleDateString();

          const total_venda = response[1][i].TOTAL_VENDA;
          const formatter_total_venda = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
          });
          const total_venda_formato = formatter_total_venda.format(total_venda);

          const val_liq = response[1][i].VALOR_LIQUIDO_PARCELA;
          const formatter_liq_parcela = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
          });
          const valor_liquido_formato = formatter_liq_parcela.format(val_liq);

          var html = "<tr>";

          // setTimeout(function () {
          html +="<td>"+"<input type='checkbox'"+"</td>";
          html +="<td>"+data_venda+"</td>";
          html +="<td>"+data_prev_pag+"</td>";
          if(response[1][i].NSU != null){
            html +="<td>"+response[1][i].NSU+"</td>";
          }else{
            html +="<td>"+""+"</td>";
          }
          html +="<td>"+total_venda_formato+"</td>";
          html +="<td>"+response[1][i].PARCELA+"</td>";
          html +="<td>"+response[1][i].TOTAL_PARCELAS+"</td>";
          html +="<td>"+valor_liquido_formato+"</td>";
          html +="<td>"+response[1][i].DESCRICAO_TIPO_PRODUTO +"</td>";
          html +="<td>"+response[1][i].CODIGO_AUTORIZACAO+"</td>";
          html +="<td>"+response[1][i].IDENTIFICADOR_PAGAMENTO+"</td>";
          html +="<td>"+response[1][i].MEIOCAPTURA+"</td>";

          html +="</tr>";
          $('#jsgrid-table-erp').append(html);
        }

        document.getElementById("resultadosPesquisaErp").style.display = "block";


        window.scrollTo(0, 550);

        document.getElementById("preloader").style.display = "none";
      }
    }
  });
});

var adquirentesSelecionados = [];
var bandeirasSelecionados = [];
var modalidadesSelecionados = [];

var el = document.getElementById('datatable');
var dragger = tableDragger.default(el, {
  dragHandler: ".handle",
  // animation: 300
  // onlyBody: false,

})
dragger.on('drop',function(from, to){
  console.log(from);
  console.log(to);
});


var flag = true;

function submit(){
  document.getElementById("preloader").style.display = "block";
  document.getElementById("preloader").style.opacity = 0.9;

  setTimeout(function () {
    document.getElementById("myform").submit();
  },200)
}

function limparCampo(grupo_clientes){
  document.getElementById("empresa").value = "";

  document.getElementById("allCheck").checked = false;
  grupo_clientes.forEach((cliente) => {
    document.getElementById(cliente.CODIGO).checked = false;
  });
}

function addSelecionadosAdquirentes(adquirentes){
  adquirentes.forEach((adquirente) => {
    if(document.getElementById(adquirente.CODIGO).checked){
      adquirentesSelecionados.includes(adquirente.ADQUIRENTE) ? '' : adquirentesSelecionados.push(adquirente.ADQUIRENTE);
    }else{
      adquirentesSelecionados.includes(adquirente.ADQUIRENTE) ? adquirentesSelecionados.splice(adquirentesSelecionados.indexOf(adquirente.ADQUIRENTE), 1) : '';
    }
  });

  document.getElementById("adquirente").value = adquirentesSelecionados;
}

function addSelecionadosBandeira(bandeiras){
  bandeiras.forEach((bandeira) => {
    if(document.getElementById(bandeira.CODIGO).checked){
      bandeirasSelecionados.includes(bandeira.BANDEIRA) ? '' : bandeirasSelecionados.push(bandeira.BANDEIRA);
    }else{
      bandeirasSelecionados.includes(bandeira.BANDEIRA) ? bandeirasSelecionados.splice(bandeirasSelecionados.indexOf(bandeira.BANDEIRA), 1) : '';
    }
  });

  document.getElementById("bandeira").value = bandeirasSelecionados;
}

function addSelecionadosModalidade(modalidades){
  modalidades.forEach((modalidade) => {
    if(document.getElementById("inputMod"+modalidade.CODIGO).checked){
      modalidadesSelecionados.includes(modalidade.DESCRICAO) ? '' : modalidadesSelecionados.push(modalidade.DESCRICAO);
    }else{
      modalidadesSelecionados.includes(modalidade.DESCRICAO) ? modalidadesSelecionados.splice(modalidadesSelecionados.indexOf(modalidade.DESCRICAO), 1) : '';
    }
  });

  document.getElementById("modalidade").value = modalidadesSelecionados;
}

function filtroCnpj(grupo_clientes){

  setTimeout(function () {
    var val_input = document.getElementById("ft").value.toUpperCase();

    if(val_input == ""){
      grupo_clientes.forEach((cliente) => {
        document.getElementById(cliente.NOME_EMPRESA).style.display = "block";
        document.getElementById(cliente.CNPJ).style.display = "block";
        document.getElementById("divCod"+cliente.CODIGO).style.display = "block";

      });
    }else{
      grupo_clientes.forEach((cliente) => {

        var regex = new RegExp(val_input);

        resultado_cnpj = cliente.CNPJ.match(regex);
        resultado = cliente.NOME_EMPRESA.match(regex);

        if(resultado || resultado_cnpj) {
          document.getElementById(cliente.NOME_EMPRESA).style.display = "block";
          document.getElementById(cliente.CNPJ).style.display = "block";
          document.getElementById("divCod"+cliente.CODIGO).style.display = "block";
        }else{
          document.getElementById(cliente.NOME_EMPRESA).style.display = "none";
          document.getElementById(cliente.CNPJ).style.display = "none";
          document.getElementById("divCod"+cliente.CODIGO).style.display = "none";
        }
      });
    }
  },300)

}

function filtroNomeModalidade(modalidades){

  setTimeout(function () {
    var val_input = document.getElementById("ftModalidade").value.toUpperCase();

    if(val_input == ""){
      modalidades.forEach((cliente) => {
        document.getElementById(cliente.DESCRICAO).style.display = "block";
        // document.getElementById(cliente.CNPJ).style.display = "block";
        document.getElementById("divCod"+cliente.CODIGO).style.display = "block";

      });
    }else{
      modalidades.forEach((cliente) => {

        var regex = new RegExp(val_input);

        resultado = cliente.DESCRICAO.match(regex);

        if(resultado) {
          document.getElementById(cliente.DESCRICAO).style.display = "block";
          document.getElementById("divCod"+cliente.CODIGO).style.display = "block";
        }else{
          document.getElementById(cliente.DESCRICAO).style.display = "none";
          document.getElementById("divCod"+cliente.CODIGO).style.display = "none";
        }
      });

    }
  },200)
}

function submitTrava(){
  document.getElementById("preloader").style.display = "block";
  document.getElementById("preloader").style.opacity = 0.2;

  setTimeout(function () {
    document.getElementById("myformTrava").submit();
  },900)
}

function checkDate(){
  var inicio = document.getElementById("date_inicial").value;
  var final = document.getElementById("date_final").value;
  submit();
}

function allCheckbox(grupo_clientes){

  grupo_clientes.forEach((cliente) => {
    if(document.getElementById("allCheck").checked){

      document.getElementById(cliente.CODIGO).checked = true;
    }else{
      document.getElementById(cliente.CODIGO).checked = false;
    }
  });
}

function allCheckboxBandeira(grupo_clientes){

  grupo_clientes.forEach((cliente) => {
    if(document.getElementById("allCheckBandeira").checked){
      document.getElementById(cliente.CODIGO).checked = true;
    }else{
      document.getElementById(cliente.CODIGO).checked = false;
    }
  });
}

function allCheckboxModalidade(grupo_clientes){

  grupo_clientes.forEach((cliente) => {
    if(document.getElementById("allCheckModalidade").checked){
      document.getElementById("inputMod"+cliente.CODIGO).checked = true;
    }else{
      document.getElementById("inputMod"+cliente.CODIGO).checked = false;
    }
  });
}

var teste = 0;

function ad(value){
  var bt = document.createElement("INPUT");
  var div_cnpjs = document.getElementById("cont");

  bt.innerHTML = value[1];

  // value = value.split("-");

  bt.setAttribute('name' , "array[]");
  bt.setAttribute('value' , value);

  // bt.style = "margin-left: 5px; margin-top:5px; width: 300px;";                   // Insert text

  bt.setAttribute('readonly', "");
  bt.style = "margin-left: 5px; margin-top:5px; margin-bottom: 3px; width: 270px;";
  // Insert text
  div_cnpjs.appendChild(bt);
}

function limparFiltros(){
  modalmodalidade = <?php echo $modalidades ?>;
  modalband = <?php echo $bandeiras ?>;

  document.getElementById("date_final").value = "";
  document.getElementById("date_inicial").value = "";
  document.getElementById("modalidade").value = "";
  document.getElementById("bandeira").value = "";

}

function addTodos(grupos_clientes){
  if(flag){
    grupos_clientes.forEach((cliente) => {
      var bt = document.createElement("INPUT");
      var div_cnpjs = document.getElementById("cont");

      // value = value.split("-");

      bt.setAttribute('name' , "array[]");
      bt.setAttribute('value' , cliente.NOME_EMPRESA + "-" +cliente.CNPJ);

      bt.style = "margin-left: 5px; margin-top:5px; width: 130px;";                   // Insert text

      bt.setAttribute('readonly', "");
      bt.style = "margin-left: 5px; margin-top:5px; margin-bottom: 3px; width: 270px;";
      // Insert text
      div_cnpjs.appendChild(bt);

      flag = false;
    });
  }
}

function removeCnpjs(){
  var array = document.getElementsByName('array[]');
  while(array[0]) {
    array[0].parentNode.removeChild(array[0]);
  }
  flag = true;
}

var mudacor = false;
function mudaCorLinhaTable(codigo){
  // if(mudacor){

  // var cor_background = $(codigo).css('background');

  var cor = document.getElementById(codigo).style.background;

  console.log(cor);

  if(cor == "" || cor == "rgb(255, 255, 255)"){

    var cor = document.getElementById(codigo).style.background = "#2d5275";
    var cor = document.getElementById(codigo).style.color = "#ffffff";


    // console.log(cor);
    // document.getElementById(codigo).style = "background: ##2D5275; color: #2D5275";
    // mudacor = false;

    // document.getElementById(codigo).style = "color: #2D5275";
    // console.log(document.getElementById(codigo));
  }

  else{
    document.getElementById(codigo).style = "background: #ffffff; color: #231F20";
  }
}

function conciliar(){
  var vendas = localStorage.getItem("vendas");

  console.log(vendas);

  vendas.forEach((venda) => {
    oonsole.log(venda.DATA_VENDA);
  })
}

</script>
@stop
