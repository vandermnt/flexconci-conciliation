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
  <!-- <form id="myform" action="{{ action('ConciliacaoAutomaticaVendasController@conciliarManualmente')}}" method="post">
  <input type ="hidden" name="_token" value="{{{ csrf_token() }}}"> -->

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
                      <input id="empresa" style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" name="adquirente">
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




      <div id="resultadosPesquisa" style="display: none">

        <div class="row">
          <div class="col-sm-2">
            <div class="card report-card"  style="width: 180px">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-12" style="text-align: center">
                    <p class="text-dark font-weight-semibold font-12">VENDAS ERP</p>
                    <div class="row">
                      <div class="col-6">
                        <h4 id="total_registros">0</h4>

                      </div>
                      <div class="col-6">
                        <img style="width: 40px;" src="{{ url('assets/images/conciliacao/vendaserp.png')}}" alt="">

                      </div>

                    </div>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                </div>

              </div><!--end card-body-->
            </div><!--end card-->
          </div> <!--end col-->
          <div class="col-sm-2">
            <div class="card report-card"  style="width: 180px">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-12" style="text-align: center">
                    <p class="text-dark font-weight-semibold font-12">CONCILIADO</p>
                    <div class="row">
                      <div class="col-6">
                        <h4 id="total_registros">0</h4>

                      </div>
                      <div class="col-6">
                        <img style="width: 40px;" src="{{ url('assets/images/conciliacao/conciliado.png')}}" alt="">

                      </div>

                    </div>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                </div>

              </div><!--end card-body-->
            </div><!--end card-->
          </div> <!--end col-->
          <div class="col-1">
            <div class="card report-card"  style="width: 180px">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-12" style="text-align: center">
                    <p class="text-dark font-weight-semibold font-12">DIVERGENTE</p>
                    <div class="row">
                      <div class="col-9">
                        <h6 style="font-size: 12px" id="total_registros">R$ 12.500.000,15</h6>

                      </div>
                      <div class="col-3">
                        <img style="width: 30px;" src="{{ url('assets/images/conciliacao/conciliadodiv.png')}}" alt="">

                      </div>

                    </div>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                </div>
                <div class="row d-flex ">
                  <div class="col-8">

                  </div>
                </div>
              </div><!--end card-body-->
            </div><!--end card-->
          </div> <!--end col-->
          <div class="col-1">
            <div class="card report-card"  style="width: 180px">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-12" style="text-align: center">
                    <p class="text-dark font-weight-semibold font-12">CONC. MANUAL</p>
                    <div class="row">
                      <div class="col-8">
                        <h6 id="total_registros">R$ 15.000,15</h6>

                      </div>
                      <div class="col-4">
                        <img style="width: 30px;" src="{{ url('assets/images/conciliacao/conciliadomanualmente.png')}}" alt="">

                      </div>

                    </div>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                </div>

              </div><!--end card-body-->
            </div><!--end card-->
          </div> <!--end col-->
          <div class="col-1">
            <div class="card report-card" style="width: 180px">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-12" style="text-align: center">
                    <p class="text-dark font-weight-semibold font-12">JUSTIFICADO</p>
                    <div class="row">
                      <div class="col-6">
                        <h6 id="total_registros">R$ 15.000,15</h6>

                      </div>
                      <div class="col-6">
                        <img style="width: 30px;" src="{{ url('assets/images/conciliacao/justificado.png')}}" alt="">

                      </div>

                    </div>                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>

                </div>
              </div><!--end card-body-->
            </div><!--end card-->
          </div> <!--end col-->
          <div class="col-1">
            <div class="card report-card" style="width: 180px">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-12" style="text-align: center">
                    <p class="text-dark font-weight-semibold font-12">PENDÊNCIAS ERP</p>
                    <div class="row">
                      <div class="col-6">
                        <h6 id="total_registros">R$ 15.000,15</h6>

                      </div>
                      <div class="col-6">
                        <img style="width: 30px;" src="{{ url('assets/images/conciliacao/vendaserpnotconc.png')}}" alt="">

                      </div>

                    </div>                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>

                </div>
              </div><!--end card-body-->
            </div><!--end card-->
          </div> <!--end col-->
          <div class="col-1">
            <div class="card report-card" style="width: 180px">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-12" style="text-align: center">
                    <p class="text-dark font-weight-semibold font-12">PENDÊNCIAS OPER.</p>
                    <div class="row">
                      <div class="col-6">
                        <h6 id="total_registros">R$ 15.000,15</h6>

                      </div>
                      <div class="col-6">
                        <img style="width: 30px;" src="{{ url('assets/images/conciliacao/vendasoperadoranotconc.png')}}" alt="">

                      </div>

                    </div>                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>

                </div>
              </div><!--end card-body-->
            </div><!--end card-->
          </div> <!--end col-->

        </div>


        <br>

        <h4> Vendas {{$erp}}</h4>
        <div style="overflow: scroll; font-size: 13px; overflow-x: scroll; max-height: 270px">

          <!-- <div style="font-size: 13px; overflow-y: auto; max-height: 270px"> -->
          <table id="jsgrid-table-erp" class="table " style="white-space: nowrap; background:white; color: #2D5275">
            <tr style="background: #2D5275;">
              <th>  </th>
              <th> Data Venda  </th>
              <th> Previs. PGT  </th>
              <th> NSU  </th>
              <th> Total Venda </th>
              <th> Nº Parcela </th>
              <th> Total Parcela </th>
              <th> Líq. Parcela </th>
              <th> Descrição ERP </th>
              <th> Cod. Autorização </th>
              <th> ID. Venda Cliente  </th>
              <th> Meio de Captura  </th>
              <th> Ação </th>
            </tr>
            <tbody>
            </tbody>
          </table>
        </div>
        <br>

        <h4> Vendas </h4>
        <div style="overflow: scroll; font-size: 13px; overflow-x: scroll; max-height: 270px">
          <table id="jsgrid-table" class="table " style="white-space: nowrap; background:white; color: #2D5275">

            <thead>
              <tr style="background: #2D5275;">
                <th>  </th>
                <th> Empresa </th>
                <th> CNPJ  </th>
                <th> Operadora </th>
                <th> Dt.Venda </th>
                <th> Dt.Prevista </th>
                <th> Bandeira </th>
                <th> Forma de Pagamento </th>
                <th> NSU</th>
                <th> Autorização</th>
                <th> Cartão </th>
                <th> Valor Bruto</th>
                <th> Taxa % </th>
                <th> Taxa R$</th>
                <th> Outras Tarifas </th>
                <th> Valor Líquido </th>
                <th> Parcela</th>
                <th> Total Parc.</th>
                <th> Hora</th>
                <th> Estabelecimento </th>
                <th> Banco </th>
                <th> Agência </th>
                <th> Conta</th>
                <th> Observação</th>
                <th> Produto </th>
                <th> Meio de Captura </th>
                <th> Ação </th>
              </tr>
              <tbody>
              </tbody>
            </table>
          </div>
          <br>
          <button type="button" onclick="conciliar()" style="background: #2D5275; box-shadow: none" class="btn btn-primary btn-lg btn-block"><b>Conciliar</b></button>

        </div>



        <div id="modal_conciliacao_manual" class="modal" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header" style="background: #2D5275;">
                <h5 class="modal-title" style="color: white">Conciliação Manual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h6 id="success_venda">Venda com código 1515 com data 12/12/2020 foi conciliada manualmente com sucesso!</h6>
                <h6 id="success_venda_erp">Venda ERP com código 1515 com data 12/12/2020 foi conciliada manualmente com sucesso!</h6>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn" style="background: #2D5275; color: white" data-dismiss="modal">Fechar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modal_justificativas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header" style="background: #2D5275">
                <h5 class="modal-title" id="exampleModalLabel" style="color: white">Justificativas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <table  class="tableDadosDash" style="width:430px; text-align: center; justify-content: center; align-items: center" >
                  <tr style="padding: 20px">
                    <th></th>
                    <th> Justificativa</th>
                  </tr>
                  @foreach($justificativas as $justificativa)
                  <tr style="padding: 20px 60px">
                    <td> <input id="{{ "just".$justificativa->CODIGO }}" type="checkbox"> </td>
                    <td>{{ $justificativa->JUSTIFICATIVA}} </td>
                  </tr>
                  @endforeach
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" onclick="justificar({{$justificativa->CODIGO}})" class="btn btn" style="background: #2D5275; color: white" data-dismiss="modal">Justificar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modal_justificativas_erp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header" style="background: #2D5275">
                <h5 class="modal-title" id="exampleModalLabel" style="color: white">Justificativas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <table  class="tableDadosDash" style="width:430px; text-align: center; justify-content: center; align-items: center" >
                  <tr style="padding: 20px">
                    <th></th>
                    <th> Justificativa</th>
                  </tr>
                  @foreach($justificativas as $justificativa)
                  <tr style="padding: 20px 60px">
                    <td> <input id="{{ "justErp".$justificativa->CODIGO }}" type="checkbox"> </td>
                    <td>{{ $justificativa->JUSTIFICATIVA}} </td>
                  </tr>
                  @endforeach
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" onclick="justificarErp({{$justificativa->CODIGO}})" class="btn btn" style="background: #2D5275; color: white" data-dismiss="modal">Justificar</button>
              </div>
            </div>
          </div>
        </div>

        <!-- <div class="modal fade" id="modal_justificativas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header" style="background: #2D5275;">
        <h5 class="modal-title" style="color: white">Justificativas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
    <table>
    <tr>
    <th></th>
    <th>Data Cadastro</th>
    <th>Justificativa</th>
  </tr>
  <tbody>
  @foreach($justificativas as $justificativa)
  <td> <input type="checkbox"> </td>
  <td> {{ $justificativa->DATA_CADASTRO }} </td>
  <td> {{ $justificativa->JUSTIFICATIVA }}</td>
  @endforeach
</tbody>
</table>

</div>
<div class="modal-footer">
<button type="button" class="btn" style="background: #2D5275; color: white" data-dismiss="modal">Fechar</button>
</div>
</div>
</div>
</div> -->


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
  // $("#jsgrid-table input").keyup(function(){
  //   var index = $(this).parent().index();
  //   var nth = "#jsgrid-table td:nth-child("+(index+1).toString()+")";
  //   var valor = $(this).val().toUpperCase();
  //   $("#jsgrid-table tbody tr").show();
  //   $(nth).each(function(){
  //     if($(this).text().toUpperCase().indexOf(valor) < 0){
  //       $(this).parent().hide();
  //     }
  //   });
  // });

  // $("#jsgrid-table input").blur(function(){
  //   $(this).val("");
  // });
});

$('#submitFormLogin').click(function(){

  $('#jsgrid-table tbody').empty();

  array = [];
  arrayStatusConciliacao = [];

  data_inicial = document.getElementById("date_inicial").value;
  data_final = document.getElementById("date_final").value;

  grupo_clientes = <?php echo $grupos_clientes ?>;
  status_conciliacao = <?php echo $status_conciliacao ?>;

  grupo_clientes.forEach((grupo_cliente) => {
    if(document.getElementById(grupo_cliente.CODIGO).checked){
      array.push(grupo_cliente.CNPJ);
    }
  });

  document.getElementById("preloader").style.display = "block";

  $.ajax({
    url: "{{ url('conciliacao-manual') }}",
    type: "post",
    header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: ({_token: '{{csrf_token()}}' , data_inicial, data_final, array, arrayStatusConciliacao}),
    dataType: 'json',
    success: function (response){

      var jsonVendas = JSON.stringify(response[0]);
      var jsonVendasERP = JSON.stringify(response[1]);

      localStorage.setItem("vendas", jsonVendas);
      localStorage.setItem("vendaserp", jsonVendasERP);

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
          html +="<td>"+"<input id='vendas-"+response[0][i].CODIGO+"' type='checkbox'"+"</td>";

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
          html +="<td>"+response[0][i].OUTRAS_DESPESAS+"</td>";
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
          html +="<td>"+"<a type='button' onclick='saveIdVenda("+response[0][i].CODIGO+")' class='btn btn-success' data-toggle='modal' data-target='#modal_justificativas'>" + "<b>"+'Justificar'+"</b>" +"</a>"+"</td>";

          // var url = "{{ url('/impressao-vendas')}}"+"/"+response[0][i].COD;
          var url = "#";

          html +="</tr>";

          $('#jsgrid-table').append(html);
          // },100);
        }

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

          var html_erp = "<tr>";

          // setTimeout(function () {
          html_erp +="<td>"+"<input id='vendaserp-"+response[1][i].CODIGO+"' type='checkbox'"+"</td>";
          html_erp +="<td>"+data_venda+"</td>";
          html_erp +="<td>"+data_prev_pag+"</td>";
          if(response[1][i].NSU != null){
            html_erp +="<td>"+response[1][i].NSU+"</td>";
          }else{
            html_erp +="<td>"+""+"</td>";
          }
          html_erp +="<td>"+total_venda_formato+"</td>";
          html_erp +="<td>"+response[1][i].PARCELA+"</td>";
          html_erp +="<td>"+response[1][i].TOTAL_PARCELAS+"</td>";
          html_erp +="<td>"+valor_liquido_formato+"</td>";
          html_erp +="<td>"+response[1][i].DESCRICAO_TIPO_PRODUTO +"</td>";
          html_erp +="<td>"+response[1][i].CODIGO_AUTORIZACAO+"</td>";
          html_erp +="<td>"+response[1][i].IDENTIFICADOR_PAGAMENTO+"</td>";
          html_erp +="<td>"+response[1][i].MEIOCAPTURA+"</td>";
          html_erp +="<td>"+"<a type='button' onclick='saveIdVendaErp("+response[1][i].CODIGO+")' class='btn btn-success' data-toggle='modal' data-target='#modal_justificativas_erp'>" + "<b>"+'Justificar'+"</b>" +"</a>"+"</td>";


          html_erp +="</tr>";
          $('#jsgrid-table-erp').append(html_erp);
        }

        document.getElementById("resultadosPesquisa").style.display = "block";

        window.scrollTo(0, 550);

        document.getElementById("preloader").style.display = "none";
      }
    }
  });
});

var adquirentesSelecionados = [];
var bandeirasSelecionados = [];
var modalidadesSelecionados = [];

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

  document.getElementById("date_final").value = "";
  document.getElementById("date_inicial").value = "";
  document.getElementById("empresa").value = "";

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
  var vendaserp = localStorage.getItem("vendaserp");
  var count_vendas = 0;
  var count_vendaserp = 0;
  let venda_conciliada;
  let vendaerp_conciliada;

  vendas = JSON.parse(vendas);
  vendaserp = JSON.parse(vendaserp);

  vendas.forEach((venda) => {
    if(document.getElementById("vendas-"+venda.CODIGO).checked == true){
      count_vendas++;
    }
  });

  if(count_vendas == 1){
    vendas.forEach((venda) => {
      if(document.getElementById("vendas-"+venda.CODIGO).checked == true){
        venda_conciliada = venda.CODIGO;
      }
    });
  }else if(count_vendas > 1){
    alert("Selecione apenas uma Venda!");
  }else if(count_vendas < 1){
    alert("Selecione uma Venda!");
  }

  vendaserp.forEach((vendaerp) => {
    if(document.getElementById("vendaserp-"+vendaerp.CODIGO).checked == true){
      count_vendaserp++;
    }
  });

  if(count_vendaserp == 1){
    vendaserp.forEach((vendaerp) => {
      if(document.getElementById("vendaserp-"+vendaerp.CODIGO).checked == true){
        vendaerp_conciliada = vendaerp.CODIGO;
      }
    });
  }else if(count_vendaserp > 1){
    alert("Selecione apenas uma Venda ERP!");
  }else if(count_vendaserp < 1){
    alert("Selecione uma Venda ERP!");
  }

  $("#modal_conciliacao_manual").modal({
    show:true
  });

  var result_venda = vendas.find(venda => venda.CODIGO == venda_conciliada);
  var result_vendaerp = vendaserp.find(vendaerp => vendaerp.CODIGO == vendaerp_conciliada)

  var data_venda = new Date(result_venda.DATA_VENDA);
  var data_venda_formatada = data_venda.toLocaleDateString();

  var data_venda_erp = new Date(result_vendaerp.DATA_VENDA);
  var data_venda_erp_formatada = data_venda_erp.toLocaleDateString();


  document.getElementById("success_venda").innerHTML = "Venda Operadora com código " + result_venda.CODIGO + ", com data da venda = " + data_venda_formatada + " foi conciliada manualmente com sucesso!";
  document.getElementById("success_venda_erp").innerHTML = "Venda ERP com código " + result_vendaerp.CODIGO + ", com data da venda = " + data_venda_erp_formatada + " foi conciliada manualmente com sucesso!";


  // $("#modal_conciliacao_manual").show();


  console.log(venda_conciliada);
}

function saveIdVenda(codigo_venda){
  localStorage.setItem("codigo_venda", codigo_venda);
}

function saveIdVendaErp(codigo_venda){
  localStorage.setItem("codigo_venda_erp", codigo_venda);
}

function justificar(codigo_justificativa){
  var cod_venda = localStorage.getItem("codigo_venda");
  var count_venda = 0
  var justificativas = <?php echo $justificativas ?>;

  justificativas.forEach((justificativa) => {
    if(document.getElementById("just"+justificativa.CODIGO).checked){
      count_venda++;
    }
  });

  if(count_venda > 1){
    alert("Escolha apenas uma justificativa!");
  }else{

    $.ajax({
      url:  "{{ url('/conciliacao-justificada-venda')}}",
      type: "post",
      header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data: ({_token: '{{csrf_token()}}', cod_venda}),
      dataType: 'json',
      success: function(response){
        console.log(response);
      }
    })
  }
}

function justificarErp(codigo_justificativa){
  var cod_venda_erp = localStorage.getItem("codigo_venda_erp");
  var count_venda = 0
  var justificativas = <?php echo $justificativas ?>;

  justificativas.forEach((justificativa) => {
    if(document.getElementById("justErp"+justificativa.CODIGO).checked){
      count_venda++;
    }
  });

  if(count_venda > 1){
    alert("Escolha apenas uma justificativa!");
  }else{

    $.ajax({
      url:  "{{ url('/conciliacao-justificada-vendaerp')}}",
      type: "post",
      header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data: ({_token: '{{csrf_token()}}', cod_venda_erp}),
      dataType: 'json',
      success: function(response){
        console.log(response);
        alert("Venda Justificada!");
      }
    })
  }
}

</script>
@stop
