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
      @slot('title') Vendas Operadoras @endslot
      @slot('item1') Vendas @endslot
      <!-- @slot('item2') Antecipação de Venda @endslot -->
      @endcomponent

    </div>
  </div>
  <form id="myform" action="{{ action('VendasController@buscarVendasFiltro')}}" method="post">
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

            <div class="row" style="margin-top: -16px">
              <div class="col-sm-6">
                <div id="filtroempresa">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-12">
                        <h6 style="color: #424242; font-size: 11.5px"> Empresa: </h6>
                        <input id="empresa" style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" name="empresa">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-2">
                <button id="buttonpesquisar" type="button" class="btn btn-sm" data-toggle="modal" data-target="#staticBackdrop" style="margin-top: 25px; width: 100%">
                  <b>Selecionar</b>
                </button>
              </div>

              <div class="col-sm-6" style="margin-top: -16px">
                <div id="filtroempresa">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-12">
                        <h6 style="color: #424242; font-size: 11.5px"> Adquirente: </h6>
                        <input id="adquirente" style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" name="adquirente">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-2">
                <button id="buttonpesquisar" type="button" class="btn btn-sm" data-toggle="modal" data-target="#staticBackdropAdquirente" style="margin-top: 9px; width: 100%">
                  <b>Selecionar</b>
                </button>
              </div>

              <div class="col-sm-6" style="margin-top: -16px">
                <div id="filtroempresa">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-12">
                        <h6 style="color: #424242; font-size: 11.5px"> Bandeira: </h6>
                        <input id="bandeira" style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" name="bandeira">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-2">
                <button id="buttonpesquisar" type="button" class="btn btn-sm" data-toggle="modal" data-target="#staticBackdropBandeira" style="margin-top: 9px; width: 100%">
                  <b>Selecionar</b>
                </button>
              </div>

              <div class="col-sm-6" style="margin-top: -16px">
                <div id="filtroempresa">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-12">
                        <h6 style="color: #424242; font-size: 11.5px"> Forma de Pagamento: </h6>
                        <input id="modalidade" style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" name="modalidade">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-2">
                <button id="buttonpesquisar" type="button" class="btn btn-sm" data-toggle="modal" data-target="#staticBackdropModalidade" style="margin-top: 9px; width: 100%">
                  <b>Selecionar</b>
                </button>
              </div>

              <div class="col-sm-6" style="margin-top: -16px">
                <div id="filtroempresa">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-12">
                        <h6 style="color: #424242; font-size: 11.5px"> Meio de Captura: </h6>
                        <input id="meiocaptura" style="margin-top: -5px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px; border-color: #2D5275" class="form-control" name="meiocaptura">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-2">
                <button id="buttonpesquisar" type="button" class="btn btn-sm" data-toggle="modal" data-target="#staticBackdropMeioCaptura" style="margin-top: 9px; width: 100%">
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

            <div class="row" style="margin-top: -12px">

              <div class="col-sm-12">
                <h6 style="color: #424242; font-size:12px"> Status Financeiro: </h6>
                <div class="row">
                  <div class="col-sm-1">
                    <div style="margin-top: -10px">
                      <input type="checkbox" checked value="1" name="status_financeiro[]" id="aberto">
                      <label style="font-size: 12px; color: #424242; margin-top: 5px" for="aberto">Em Aberto</label>
                    </div>
                  </div>
                  <div class="col-sm-1">
                    <div style="margin-top: -10px">
                      <input type="checkbox" checked value="2" name="status_financeiro[]" id="liquidado">
                      <label style="font-size: 12px; color: #424242; margin-top: 5px" for="liquidado">Liquidado</label>
                    </div>
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
          <div class="modal-dialog" style="width: 100%;">
            <div class="modal-content">
              <div class="modal-header" style="background: #2D5275;">
                <h5 class="modal-title" id="staticBackdropLabel" style="color: white">Empresa</h5>
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

        <div class="modal fade" id="staticBackdropAdquirente" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog" style="width: 250px;">
            <div class="modal-content">
              <div class="modal-header" style="background: #2D5275;">
                <h5 class="modal-title" id="staticBackdropLabel" style="color: white">Adquirente</h5>
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
                    <input id="ft" style="margin-top: -6px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px" class="form-control" onKeyDown="filtroNomeAdquirente({{$adquirentes}})">
                  </div>

                </div><br>

                <div class="row">
                  <div class="col-sm-10">
                    <p><b>Adquirente</b></p>
                  </div>

                  <div class="col-sm-2">
                    <input id="allCheckAd" onchange="allCheckboxAd({{$adquirentes}})" type="checkbox">
                  </div>
                  @if(isset($adquirentes))
                  @foreach($adquirentes as $adquirente)
                  <div id="{{ $adquirente->ADQUIRENTE }}" style="display:block; " class="col-sm-10">
                    <p>{{ $adquirente->ADQUIRENTE }}</p>
                  </div>

                  <div id="{{ "divCod".$bandeira->CODIGO }}" style="display:block" class="col-sm-2">
                    <input id="{{ $adquirente->CODIGO }}" value="{{ $adquirente->ADQUIRENTE }}" name="arrayAdquirentes[]" type="checkbox">
                  </div>
                  <hr>
                  @endforeach
                  @endif
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="addSelecionadosAdquirentes({{$adquirentes}})"><b>Confirmar</b></button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="staticBackdropBandeira" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog" style="width: 250px; ">
            <div class="modal-content">
              <div class="modal-header" style="background: #2D5275;">
                <h5 class="modal-title" id="staticBackdropLabel" style="color: white">Bandeira</h5>
                <button style="color: white" type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="max-height: 350px; overflow: auto">
                <div class="row">
                  <div class="col-sm-12">
                    <h6> Pesquisar </h6>
                  </div>
                  <div class="col-sm-12">
                    <input id="ft" style="margin-top: -6px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px" class="form-control" onKeyDown="filtroNomeBandeira({{$bandeiras}})">
                  </div>

                </div><br>

                <div class="row">
                  <div class="col-sm-10">
                    <p><b>BANDEIRA</b></p>
                  </div>

                  <div class="col-sm-2">
                    <input id="allCheckBandeira" onchange="allCheckboxBandeira({{$bandeiras}})" type="checkbox">
                  </div>
                  @if(isset($bandeiras))
                  @foreach($bandeiras as $bandeira)

                  <div id="{{ $bandeira->BANDEIRA }}" style="display:block; " class="col-sm-10">
                    <p>{{ $bandeira->BANDEIRA }}</p>
                  </div>

                  <div id="{{ "divCod".$bandeira->CODIGO }}" style="display:block" class="col-sm-2">
                    <input id="{{ $bandeira->CODIGO }}" value="{{ $bandeira->CODIGO }}" name="arrayBandeira[]" type="checkbox">
                  </div>
                  <hr>
                  @endforeach
                  @endif
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="addSelecionadosBandeira({{$bandeiras}})"><b>Confirmar</b></button>
              </div>
            </div>
          </div>
        </div>


        <div class="modal fade" id="staticBackdropModalidade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog" style="width: 270px">
            <div class="modal-content">
              <div class="modal-header" style="background: #2D5275;">
                <h5 class="modal-title" id="staticBackdropLabel" style="color: white">Forma de Pagamento</h5>
                <button type="button" style="color: white" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="max-height: 350px; overflow: auto">
                <div class="row">
                  <div class="col-sm-12">
                    <h6> Pesquisar </h6>
                  </div>
                  <div class="col-sm-12">
                    <input id="ftModalidade" style="margin-top: -6px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px" class="form-control" onKeyDown="filtroNomeModalidade({{$modalidades}})">
                  </div>

                </div> <br>

                <div class="row">
                  <div class="col-sm-10">
                    <p><b>FORMA DE PAGAMENTO</b></p>
                  </div>

                  <div class="col-sm-2">
                    <input id="allCheckModalidade" onchange="allCheckboxModalidade({{$modalidades}})" type="checkbox">
                  </div>
                  @if(isset($modalidades))
                  @foreach($modalidades as $modalidade)

                  <div id="{{ $modalidade->DESCRICAO }}" style="display:block" class="col-sm-10">
                    <p>{{ $modalidade->DESCRICAO }}</p>
                  </div>
                  <div id="{{ "divCod".$modalidade->CODIGO }}" style="display:block" class="col-sm-2">
                    <input id="{{ "inputMod".$modalidade->CODIGO }}" value="{{ $modalidade->CODIGO }}" name="arrayModalidade[]" type="checkbox">
                  </div>
                  <hr>
                  @endforeach
                  @endif
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="addSelecionadosModalidade({{$modalidades}})"><b>Confirmar</b></button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="staticBackdropMeioCaptura" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog" style="width: 270px">
            <div class="modal-content">
              <div class="modal-header" style="background: #2D5275;">
                <h5 class="modal-title" id="staticBackdropLabel" style="color: white">Meio de Captura</h5>
                <button type="button" style="color: white" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="max-height: 350px; overflow: auto">
                <div class="row">
                  <div class="col-sm-12">
                    <h6> Pesquisar </h6>
                  </div>
                  <div class="col-sm-12">
                    <input id="ftMeioCaptura" style="margin-top: -6px; padding-left: 7px; padding-top: 5px; padding-bottom: 5px; height: 30px" class="form-control" onKeyDown="filtroMeioCaptura({{$meio_captura}})">
                  </div>

                </div> <br>

                <div class="row">
                  <div class="col-sm-10">
                    <p><b>MEIO DE CAPTURA</b></p>
                  </div>

                  <div class="col-sm-2">
                    <input id="allCheckMeioCaptura" onchange="allCheckboxMeioCaptura({{$meio_captura}})" type="checkbox">
                  </div>
                  @if(isset($meio_captura))
                  @foreach($meio_captura as $meio)

                  <div id="{{ $meio->DESCRICAO }}" style="display:block" class="col-sm-10">
                    <p>{{ $meio->DESCRICAO }}</p>
                  </div>
                  <div id="{{ "divCod".$meio->CODIGO }}" style="display:block" class="col-sm-2">
                    <input id="{{ "inputMeioCap".$meio->CODIGO }}" value="{{ $meio->CODIGO }}" name="arrayMeioCaptura[]" type="checkbox">
                  </div>
                  <hr>
                  @endforeach
                  @endif
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="addSelecionadosMeioCaptura({{$meio_captura}})"><b>Confirmar</b></button>
              </div>
            </div>
          </div>
        </div>
      </form>

      <!-- @if(isset($flag_scroll))
      <script>window.location.href='#foo';</script>
      @endif -->

      <div id="resultadosPesquisa" style="display: none">
        <div class="row">
          <div class="col-md-6 col-lg-3">
            <div class="card report-card">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-8">
                    <p class="text-dark font-weight-semibold font-12">QTDE DE VENDAS</p>
                    <h4 id="total_registros" class="my-3">378</h4>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img style="width: 40px" src="{{ url('assets/images/vendasoperadora/quantidade.png')}}" alt="">
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
                    <p class="text-dark font-weight-semibold font-12">VALOR BRUTO DE VENDAS</p>
                    <h4 id="total_bruto_vendas" class="my-3">R$ 240,000,00</h4>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img style="width: 40px" src="{{ url('assets/images/vendasoperadora/bruto.png')}}" alt="">
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
                    <p class="text-dark font-weight-semibold font-12">VALOR DE TAXAS COBRADAS</p>
                    <h4 id="total_taxa_cobrada" class="my-3">R$ 240,000,00</h4>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img style="width: 40px" src="{{ url('assets/images/vendasoperadora/percentagem.png')}}" alt="">
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
                    <p class="text-dark font-weight-semibold font-12">VALOR LÍQUIDO DE VENDAS</p>
                    <h4 id="total_liquido_vendas" class="my-3">R$ 240,000,00</h4>
                    <!-- <p class="mb-0 text-truncate"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img style="width: 40px" src="{{ url('assets/images/vendasoperadora/liquido.png')}}" alt="">
                    </div>
                  </div>
                </div>
              </div><!--end card-body-->
            </div><!--end card-->
          </div> <!--end col-->

        </div>
        <div class="row" id="foo">
          <div  class="col-sm-2">

          </div>

          <div class="col-sm-10" align="right">
            <div class="dropdown">
              <a class="btn btn-sm dropdown-toggle" style="width: 160px; background: white; color: #2D5275; border-color: #2D5275" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i style="font-size: 19px" class="fas fa-file-download" style="padding: 7px"></i> <b style="font-size: 12px; margin-left: ">Exportar</b>
              </a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" id="dp-item"  href="{{ action('VendasController@downloadTable') }}">  PDF</a>
                <a class="dropdown-item" id="dp-item"  onclick="download_table_as_csv('jsgrid-table');" href="#">  CSV</a>
              </div>
            </div>
          </div>

        </div>


        <br>
        <div style="font-size: 13px; overflow: scroll; height: 470px">

          <table id="jsgrid-table" class="table" style="white-space: nowrap; border: none">

            <thead>
              <tr style="border-top: none">
                <th> Detalhes </th>
                <th> Empresa  <br> <input style="max-width: 135px; margin: 0"></th>
                <th> CNPJ  <br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Operadora<br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Dt.Venda  <br> <input style="max-width: 135px; margin: 0"></th>
                <th> Dt.Prevista <br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Bandeira<br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Forma de Pagamento<br> <input style="max-width: 135px; margin: 0"> </th>
                <th> NSU <br> <input style="max-width: 135px; margin: 0"></th>
                <th> Autorização <br> <input style="max-width: 135px; margin: 0"></th>
                <th> Cartão<br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Valor Bruto <br> <input style="max-width: 135px; margin: 0"></th>
                <th> Taxa %  <br> <input style="max-width: 135px; margin: 0"></th>
                <th> Taxa R$ <br> <input style="max-width: 135px; margin: 0"></th>
                <th> Outras Tarifas<br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Valor Líquido <br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Parcela <br> <input style="max-width: 135px; margin: 0"></th>
                <th> Total Parc. <br> <input style="max-width: 135px; margin: 0"></th>
                <th> Hora <br> <input style="max-width: 135px; margin: 0"></th>
                <th> Estabelecimento<br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Banco<br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Agência<br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Conta <br> <input style="max-width: 135px; margin: 0"></th>
                <th> Observação <br> <input style="min-width: 135px; margin: 0"></th>
                <th> Produto<br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Meio de Captura<br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Status Conciliação<br> <input style="max-width: 135px; margin: 0"> </th>
                <th> Status Financeiro<br> <input style="max-width: 135px; margin: 0"> </th>

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
                <td>{{$results->AUTORIZACAO}}</td>
                <td>{{$results->CARTAO}}</td>
                <?php $val_brt = number_format($results->VALOR_BRUTO,2,",","."); ?>
                <td>{{$val_brt}}</td>
                <td><?php echo number_format( $results->VALOR_TAXA,2,",","."); ?></td>
                <td><?php echo number_format( $results->VALOR_TAXA,2,",","."); ?> </td>
                <?php $val_liq = number_format($results->VALOR_LIQUIDO,2,",","."); ?>
                <td>{{$val_liq}}</td>
                <td>{{ $results->PARCELA }}</td>
                <td>{{ $results->TOTAL_PARCELAS }}</td>
                <td>{{ $results->HORA_TRANSACAO }} </td>
                <td>{{ $results->ESTABELECIMENTO }}</td>
                <td>{{ $results->BANCO }}</td>
                <td>{{ $results->AGENCIA }}</td>
                <td>{{ $results->CONTA }}</td>
                <td>botoões aquii</td>
              </tr>
              @endforeach
            </tbody>

          </table>

          <!--
          <div style="background: red;  width: 120px;margin-bottom: 0">
          <h1>odwkaodpawkdp</h1>
        </div> -->

      </div>

      <!-- <div class="col-sm-12">
      <div class="row" style="align-items: center;">
      <div class="col-sm-2">
      <h6 id="total_registros"> Total Registros: {{ $qtde_registros }} </h6>
    </div>
    <div style="margin-left: 10px;" class="col-sm-3">
    <h6 id="val_bruto"><b>Total Valor Bruto</b>: </h6>
  </div>
  <div style="margin-left: -50px;" class="col-sm-1.9">
  <h6 id="val_liquido"><b>Total Valor Líquido</b>: </h6>
</div>
</div>
</div> -->
</div>
<div style="display:none">
  <table id="mytable" class="table table-bordered" align="center" style="white-space: nowrap">
    <thead>
      <tr style="width: 300px">
        <th> <i class="fas fa-filter"></i> <br>Empresa </th>
        <th scope="col"><i class="fas fa-filter"></i><br>CNPJ </th>
        <th> <i class="fas fa-filter"></i> <br> Operadora</th>
        <th scope="col"><i class="fas fa-filter"></i> <br>Dt.Venda </th>
        <th scope="col"><i class="fas fa-filter"></i><br> Dt.Prevista </th>
        <th scope="col"><i class="fas fa-filter"></i><br> Bandeira </th>
        <th scope="col"><i class="fas fa-filter"></i> <br>Forma de Pagamento </th>
        <th scope="col"><i class="fas fa-filter"></i> <br>NSU </th>
        <th scope="col"> <i class="fas fa-filter"></i><br>Autorização </th>
        <th scope="col"> <i class="fas fa-filter"></i><br> Cartão</th>
        <th scope="col"><i class="fas fa-filter"></i> <br>Valor Bruto </th>
        <th scope="col"><i class="fas fa-filter"></i> <br>Taxa % </th>
        <th scope="col" > <i class="fas fa-filter"></i> <br>Taxa R$</th>
        <th scope="col"><i class="fas fa-filter"></i> <br>Valor Líquido </th>
        <th scope="col"> <i class="fas fa-filter"></i> <br>Parcela</th>
        <th scope="col"><i class="fas fa-filter"></i> <br>Total Parc. </th>
        <th scope="col"> <i class="fas fa-filter"></i><br> Hora</th>
        <th scope="col"><i class="fas fa-filter"></i> <br>Estabelecimento </th>
        <th scope="col"><i class="fas fa-filter"></i><br> Banco </th>
        <th scope="col"><i class="fas fa-filter"></i><br> Agência </th>
        <th scope="col"><i class="fas fa-filter"></i><br> Conta </th>
        <th scope="col"><i class="fas fa-filter"></i> <br>Ação </th>

      </tr>
    </thead>
    <tbody>
      <tr style="width: 500px">
        @foreach($todas_vendas as $results)
        <td style="text-align: center !important; width: 500px !important"> <?php echo  ucfirst(strtolower($results->EMPRESA)) ?> </td>
        <td style="text-align: center !important"> {{ $results->CNPJ }}</td>
        <td style="text-align: center !important">{{ $results->ADQUIRENTE }}</td>
        <?php $newDate = date("d/m/Y", strtotime($results->DATA_VENDA));?>
        <td style="text-align: center !important">{{ $newDate }} </td>
        <?php $newDatePrev= date("d/m/Y", strtotime($results->DATA_PREVISTA_PAGTO));?>
        <td style="text-align: center !important">{{ $newDatePrev }} </td>
        <td style="text-align: center !important">{{ $results->BANDEIRA }}</td>
        <td style="text-align: center !important">{{ $results->DESCRICAO }}</td>
        <td style="text-align: center !important">{{ $results->NSU }}</td>
        <td style="text-align: center !important">{{ $results->AUTORIZACAO }}</td>
        <td style="text-align: center !important">{{ $results->CARTAO }}</td>
        <td style="text-align: center !important">{{ $results->VALOR_BRUTO }}</td>
        <td style="text-align: center !important">{{ $results->PERCENTUAL_TAXA }}</td>
        <td style="text-align: center !important; padding: 0px">{{ $results->VALOR_TAXA }}</td>
        <td style="text-align: center !important">{{ $results->VALOR_LIQUIDO }}</td>
        <td style="text-align: center !important">{{ $results->PARCELA }}</td>
        <td style="text-align: center !important">{{ $results->TOTAL_PARCELAS }}</td>
        <td style="text-align: center !important"> {{ $results->HORA_TRANSACAO }} </td>
        <td style="text-align: center !important">{{ $results->ESTABELECIMENTO }}</td>
        <td style="text-align: center !important">{{ $results->BANCO }}</td>
        <td style="text-align: center !important">{{ $results->AGENCIA }}</td>
        <td style="text-align: center !important">{{ $results->CONTA }}</td>
        <td style="text-align: center !important">botoões aquii</td>
      </tr>
      @endforeach
    </table>
  </tbody>
</div>

<br>
<div style="font-size: 15px; color: red">

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
  // autenticacao = {
  // if(document.getElementById("rodapeTable")){
  //   var node = document.getElementById("557");
  //   var nodee = document.getElementById("rodapeTable");
  //
  //   if(node != null){
  //     if (node.parentNode) {
  //       node.parentNode.removeChild(node);
  //     }
  //   }
  //
  //   if(nodee != null){
  //     if (nodee.parentNode) {
  //       nodee.parentNode.removeChild(nodee);
  //     }
  //   }
  // }


  $('#jsgrid-table tbody').empty();

  array = [];
  arrayModalidade = [];
  arrayBandeira = [];
  arrayAdquirentes = [];
  arrayStatusConciliacao = [];
  arrayStatusFinanceiro = [];
  arrayMeioCaptura = [];

  data_inicial = document.getElementById("date_inicial").value;
  data_final = document.getElementById("date_final").value;

  grupo_clientes = <?php echo $grupos_clientes ?>;
  modalidades = <?php echo $modalidades ?>;
  bandeiras = <?php echo $bandeiras ?>;
  mcaptura = <?php echo $meio_captura ?>;
  adquirentes = <?php echo $adquirentes ?>;
  status_conciliacao = <?php echo $status_conciliacao ?>;
  aberto = document.getElementById("aberto").checked;
  liquidado = document.getElementById("liquidado").checked;

  if(document.getElementById("aberto").checked){
    arrayStatusFinanceiro.push(1);
  }
  if(document.getElementById("liquidado").checked){
    arrayStatusFinanceiro.push(2);
  }

  grupo_clientes.forEach((grupo_cliente) => {
    if(document.getElementById(grupo_cliente.CODIGO).checked){
      array.push(grupo_cliente.CNPJ);
    }
  });

  adquirentes.forEach((adquirente) => {
    if(document.getElementById(adquirente.CODIGO).checked){
      arrayAdquirentes.push(adquirente.CODIGO);
    }
  });

  status_conciliacao.forEach((status) => {
    if(document.getElementById("statusFinan"+status.CODIGO).checked){
      arrayStatusConciliacao.push(status.CODIGO);
    }
  });

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

  mcaptura.forEach((mcaptura) => {
    if(document.getElementById("inputMeioCap"+mcaptura.CODIGO).checked){
      arrayMeioCaptura.push(mcaptura.CODIGO);
    }
  });

  document.getElementById("preloader").style.display = "block";

  $.ajax({
    url: "{{ url('vendasoperadorasfiltro') }}",
    type: "post",
    header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: ({_token: '{{csrf_token()}}' , data_inicial, data_final, array, arrayAdquirentes, arrayBandeira, arrayModalidade, arrayStatusConciliacao, arrayStatusFinanceiro, arrayMeioCaptura}),
    dataType: 'json',
    success: function (response){
      if(response){
        console.log(response);
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
          var html = "<tr id='"+cod+"' onclick='mudaCorLinhaTable("+cod+")'>";

          // setTimeout(function () {
          html +="<td>"+"<a href='{{ url('/impressao-vendas')}}"+"/"+response[0][i].COD+"' target='_blank'><i style='font-size: 17px' class='fas fa-print'></i></a>"+"</td>";
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
          html +="<td>"+response[0][i].status_conc+"</td>";
          html +="<td>"+response[0][i].status_finan+"</td>";


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
        htmll +="<td>"+""+"</td>";
        htmll +="<td style='color:#6E6E6E'> <b>"+response[2]+"</b></td>";
        htmll +="<td>"+""+"</td>";
        htmll +="<td style='color:#6E6E6E'><b>"+response[4]+"</b></td>";
        htmll +="<td style='color:#6E6E6E'><b>"+response[6]+"</b></td>";
        htmll +="<td style='color:#6E6E6E'><b>"+response[1]+"</b></td>";
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
        htmll +="<td>"+""+"</td>";
        htmll +="<td>"+""+"</td>";



        htmll +="</tr>";
        $('#jsgrid-table').append(htmll);
        document.getElementById("resultadosPesquisa").style.display = "block";

        document.getElementById("total_liquido_vendas").innerHTML = "R$ "+response[1];
        document.getElementById("total_registros").innerHTML = response[3];
        document.getElementById("total_taxa_cobrada").innerHTML = "R$ "+response[4];
        document.getElementById("total_bruto_vendas").innerHTML = "R$ "+response[2];

        window.scrollTo(0, 550);

        document.getElementById("preloader").style.display = "none";
      }
    }
  });
});

var empresasSelecionadas = [];
var adquirentesSelecionados = [];
var bandeirasSelecionados = [];
var modalidadesSelecionados = [];
var meioCapturaSelecionados = [];

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

function addSelecionados(grupo_clientes){
  grupo_clientes.forEach((cliente) => {
    if(document.getElementById(cliente.CODIGO).checked){
      empresasSelecionadas.includes(cliente.NOME_EMPRESA) ? '' : empresasSelecionadas.push(cliente.NOME_EMPRESA);
    }else{
      empresasSelecionadas.includes(cliente.NOME_EMPRESA) ? empresasSelecionadas.splice(empresasSelecionadas.indexOf(cliente.NOME_EMPRESA), 1) : '';
    }
  });

  document.getElementById("empresa").value = empresasSelecionadas;
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

function addSelecionadosMeioCaptura(meiocaptura){
  meiocaptura.forEach((meiocaptura) => {
    if(document.getElementById("inputMeioCap"+meiocaptura.CODIGO).checked){
      meioCapturaSelecionados.includes(meiocaptura.DESCRICAO) ? '' :  meioCapturaSelecionados.push(meiocaptura.DESCRICAO);
    }else{
      meioCapturaSelecionados.includes(meiocaptura.DESCRICAO) ? meioCapturaSelecionados.splice(meioCapturaSelecionados.indexOf(meiocaptura.DESCRICAO), 1) : '';
    }
  });

  document.getElementById("meiocaptura").value = meioCapturaSelecionados;
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

function allCheckboxAd(grupo_clientes){

  grupo_clientes.forEach((cliente) => {
    if(document.getElementById("allCheckAd").checked){
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

function allCheckboxMeioCaptura(grupo_clientes){

  grupo_clientes.forEach((cliente) => {
    if(document.getElementById("allCheckMeioCaptura").checked){
      document.getElementById("inputMeioCap"+cliente.CODIGO).checked = true;
    }else{
      document.getElementById("inputMeioCap"+cliente.CODIGO).checked = false;
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
  modalempresa = <?php echo $grupos_clientes ?>;
  modalmodalidade = <?php echo $modalidades ?>;
  modalband = <?php echo $bandeiras ?>;
  modalmcaptura = <?php echo $meio_captura ?>;
  modaladq = <?php echo $adquirentes ?>;

  document.getElementById("date_final").value = "";
  document.getElementById("date_inicial").value = "";
  document.getElementById("adquirente").value = "";
  document.getElementById("modalidade").value = "";
  document.getElementById("bandeira").value = "";
  document.getElementById("empresa").value = "";
  document.getElementById("meiocaptura").value = "";

  modalempresa.forEach((grupo_cliente) => {
    document.getElementById(grupo_cliente.CODIGO).checked = false;
    document.getElementById("allCheck").checked = false;

  });

  modalmodalidade.forEach((modalidade) => {
    document.getElementById("inputMod"+modalidade.CODIGO).checked = false;
    document.getElementById("allCheckModalidade").checked = false;

  });

  modalband.forEach((bandeira) => {
    document.getElementById(bandeira.CODIGO).checked = false;
    document.getElementById("allCheckBandeira").checked = false;

  });

  modalmcaptura.forEach((meiocaptura) => {
    document.getElementById("inputMeioCap"+meiocaptura.CODIGO).checked = false;
    document.getElementById("allCheckMeioCaptura").checked = false;

  });

  modaladq.forEach((adquirente) => {
    document.getElementById(adquirente.CODIGO).checked = false;
    document.getElementById("allCheckAd").checked = false;

  });

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

function download_table_as_csv(table_id) {
  // Select rows from table_id
  var rows = document.querySelectorAll('table#' + table_id + ' tr');
  // Construct csv
  var csv = [];
  for (var i = 0; i < rows.length; i++) {
    var row = [], cols = rows[i].querySelectorAll('td, th');
    for (var j = 0; j < cols.length; j++) {
      // Clean innertext to remove multiple spaces and jumpline (break csv)
      var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
      // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
      data = data.replace(/"/g, '""');
      // Push escaped string
      row.push('"' + data + '"');
    }
    csv.push(row.join(';'));
  }
  var csv_string = csv.join('\n');
  // Download it
  var filename = 'export_' + 'conciflex' + '_' + new Date().toLocaleDateString() + '.csv';
  var link = document.createElement('a');
  link.style.display = 'none';
  link.setAttribute('target', '_blank');
  link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
  link.setAttribute('download', filename);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

var mudacor = false;
function mudaCorLinhaTable(codigo){
  // if(mudacor){

  // var cor_background = $(codigo).css('background');

  var cor = document.getElementById(codigo).style.background;

  console.log(cor);

  if(cor == "" || cor == "rgb(255, 255, 255)"){

    var cor = document.getElementById(codigo).style.background = "#A4A4A4";
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

</script>
@stop
