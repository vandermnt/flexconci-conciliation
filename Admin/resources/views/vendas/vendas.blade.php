@extends('layouts.analytics-master')

@section('title', 'Metrica - Admin & Dashboard Template')

@section('headerStyle')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.4.4/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<!-- <script src="http://tablesorter.com/__jquery.tablesorter.min.js" type="text/javascript"></script> -->

<link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/vendas/venda-operadora.css')}}" rel="stylesheet" type="text/css" />
@stop

@section('content')

<div id="preloader" class="loader"></div>

<div id="tudo_page" class="container-fluid">
  <div class="row">
    <div class="col-sm-12">

      @component('common-components.breadcrumb')
      @slot('title') Vendas Operadoras @endslot
      @slot('item1') Vendas @endslot
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
                        <h6> Data Inicial: </h6>
                        <input class="form-control inputs" type="date" id="date_inicial" value="{{  date("Y-m-01")}}" name="data_inicial" max="3000-12-31">
                      </div>
                      <div class="col-sm-6">
                        <h6> Data Final: </h6>
                        <input class="form-control inputs" type="date" id="date_final" value="{{ date("Y-m-d") }}" name="data_final">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row containers-input">
              <div class="col-sm-6">
                <div id="filtroempresa">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-12">
                        <h6> Empresa: </h6>
                        <input id="empresa" class="form-control inputs" name="empresa">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-2">
                <button id="buttonpesquisar" type="button" class="btn btn-sm" data-toggle="modal" data-target="#staticBackdrop">
                  <b>Selecionar</b>
                </button>
              </div>

              <div class="col-sm-6 containers-input">
                <div id="filtroadquirente">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-12">
                        <h6> Adquirente: </h6>
                        <input id="adquirente" class="form-control inputs" name="adquirente">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-2">
                <button id="buttonpesquisar" type="button" class="btn btn-sm bt-pesquisa" data-toggle="modal" data-target="#staticBackdropAdquirente">
                  <b>Selecionar</b>
                </button>
              </div>

              <div class="col-sm-6 containers-input">
                <div id="filtrobandeira">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-12">
                        <h6> Bandeira: </h6>
                        <input id="bandeira" class="form-control inputs" name="bandeira">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-2">
                <button id="buttonpesquisar" type="button" class="btn btn-sm bt-pesquisa" data-toggle="modal" data-target="#staticBackdropBandeira">
                  <b>Selecionar</b>
                </button>
              </div>

              <div class="col-sm-6 containers-input">
                <div id="filtromodalidade">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-12">
                        <h6> Forma de Pagamento: </h6>
                        <input id="modalidade" class="form-control inputs" name="modalidade">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-2">
                <button id="buttonpesquisar" type="button" class="btn btn-sm bt-pesquisa" data-toggle="modal" data-target="#staticBackdropModalidade">
                  <b>Selecionar</b>
                </button>
              </div>

              <div class="col-sm-6 containers-input">
                <div id="filtrocodestabelecimento">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-12">
                        <h6> Código de Estabelecimento: </h6>
                        <input id="codestabelecimento" class="form-control inputs" name="codestabelecimento">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-2">
                <button id="buttonpesquisar" type="button" class="btn btn-sm bt-pesquisa" data-toggle="modal" data-target="#staticBackdropCodEstabelecimento">
                  <b>Selecionar</b>
                </button>
              </div>
            </div>

            <div class="row containers-input">
              <div class="col-sm-12">
                <h6> Status Conciliação: </h6>
                <div class="row">
                  <div class="row">
                    @foreach($status_conciliacao as $status)
                    <div class="inputs-statusconciliacao">
                      <input type="checkbox" checked  class="checkStatusConciliacao" value="{{ $status->CODIGO }}" name="status_conciliacao[]" id="{{ "statusFinan-".$status->CODIGO }}"required>
                      <label for="{{ "statusFinan".$status->CODIGO }}">{{ $status->STATUS_CONCILIACAO}}</label>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

            <div class="row containers-input">
              <div class="col-sm-12">
                <h6> Status Financeiro: </h6>
                <div class="row">
                  <div class="col-sm-2 checks-fornulario">
                    <input type="checkbox" checked value="1" name="status_financeiro[]" id="pendente">
                    <label for="aberto">Pendente</label>
                  </div>
                  <div class="col-sm-2 checks-fornulario">
                    <input type="checkbox" checked value="2" name="status_financeiro[]" id="liquidado">
                    <label for="liquidado">Liquidada</label>
                  </div>
                  <div class="col-sm-2 checks-fornulario">
                    <input type="checkbox" checked value="2" name="status_financeiro[]" id="cancelada">
                    <label for="cancelada">Cancelada</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div id="btfiltro">
                  <a onclick="limparFiltros()" class="btn btn-sm limpar-campos"> <i class="far fa-trash-alt"></i> <b>Limpar Campos</b>  </a>
                  <a id="submitFormLogin" class="btn btn-sm"> <i class="fas fa-search"></i> <b>Pesquisar</b>  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header fundo-modal">
                <h5 class="modal-title" id="staticBackdropLabel">Empresa</h5>
              </div>
              <div class="modal-body tamanho-modal">
                <div class="row">
                  <div class="col-sm-12">
                    <h6> Pesquisar </h6>
                  </div>
                  <div class="col-sm-12">
                    <input id="ft" class="form-control" name="valor_venda" onKeyDown="filtroCnpj({{$grupos_clientes}})">
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
                  <div id="{{ $cliente->NOME_EMPRESA }}" class="col-sm-7">
                    <p>{{ $cliente->NOME_EMPRESA }}</p>
                  </div>
                  <div id="{{ $cliente->CNPJ }}" class="col-sm-4">
                    <p>{{ $cliente->CNPJ }}</p>
                  </div>
                  <div id="{{ "divCod".$cliente->CODIGO }}" class="col-sm-1">
                    <input id="{{ $cliente->CODIGO }}" class="checkEmpresa" value="{{ $cliente->CNPJ }}" name="array[]" type="checkbox">
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
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header fundo-modal">
                <h5 class="modal-title" id="staticBackdropLabel">Adquirente</h5>
              </div>
              <div class="modal-body tamanho-modal">
                <div class="row">
                  <div class="col-sm-12">
                    <h6> Pesquisar </h6>
                  </div>
                  <div class="col-sm-12">
                    <input id="inputAdq" class="form-control" onKeyDown="filtroNomeAdquirente({{$adquirentes}})">
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
                  <div id="{{ $adquirente->ADQUIRENTE }}" class="col-sm-10">
                    <p>{{ $adquirente->ADQUIRENTE }}</p>
                  </div>

                  <div id="{{ "divAdq".$adquirente->CODIGO }}" class="col-sm-2">
                    <input id="{{ "inputAdq-".$adquirente->CODIGO }}" class="checkAdquirentes" value="{{ $adquirente->CODIGO }}" name="arrayAdquirentes[]" type="checkbox">
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
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header fundo-modal">
                <h5 class="modal-title" id="staticBackdropLabel">Bandeira</h5>
              </div>
              <div class="modal-body tamanho-modal">
                <div class="row">
                  <div class="col-sm-12">
                    <h6> Pesquisar </h6>
                  </div>
                  <div class="col-sm-12">
                    <input id="inputBad" class="form-control" onKeyDown="filtroNomeBandeira({{$bandeiras}})">
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

                  <div id="{{ $bandeira->BANDEIRA }}" class="col-sm-10">
                    <p>{{ $bandeira->BANDEIRA }}</p>
                  </div>

                  <div id="{{ "divBad".$bandeira->CODIGO }}" class="col-sm-2">
                    <input id="{{ $bandeira->CODIGO }}" class="checkBandeira" value="{{ $bandeira->CODIGO }}" name="arrayBandeira[]" type="checkbox">
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
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header fundo-modal">
                <h5 class="modal-title" id="staticBackdropLabel">Forma de Pagamento</h5>
              </div>
              <div class="modal-body tamanho-modal">
                <div class="row">
                  <div class="col-sm-12">
                    <h6> Pesquisar </h6>
                  </div>
                  <div class="col-sm-12">
                    <input id="ftModalidade" class="form-control" onKeyDown="filtroNomeModalidade({{$modalidades}})">
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

                  <div id="{{ $modalidade->DESCRICAO }}" class="col-sm-10">
                    <p>{{ $modalidade->DESCRICAO }}</p>
                  </div>
                  <div id="{{ "divCod".$modalidade->CODIGO }}" class="col-sm-2">
                    <input id="{{ "inputMod-".$modalidade->CODIGO }}" class="checkModalidade" value="{{ $modalidade->CODIGO }}" name="arrayModalidade[]" type="checkbox">
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

        <div class="modal fade" id="staticBackdropCodEstabelecimento" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header fundo-modal">
                <h5 class="modal-title" id="staticBackdropLabel">Código de Estabelecimento</h5>
              </div>
              <div class="modal-body tamanho-modal">
                <div class="row">
                  <div class="col-sm-12">
                    <h6> Pesquisar </h6>
                  </div>
                  <div class="col-sm-12">
                    <input id="inputCodEstabelecimento" class="form-control" onKeyDown="filtroCodEstabelecimento({{$cod_estabelecimento}})">
                  </div>
                </div> <br>
                <div class="row">
                  <div class="col-sm-9">
                    <p><b>CÓDIGO DE ESTABELEC.</b></p>
                  </div>
                  <div class="col-sm-3">
                    <input id="allCheckCodEstabelecimento" onchange="allCheckboxMeioCaptura({{$cod_estabelecimento}})" type="checkbox">
                  </div>
                  @if(isset($cod_estabelecimento))
                  @foreach($cod_estabelecimento as $estabelecimento)

                  <div id="{{ $estabelecimento->CODIGO_ESTABELECIMENTO }}" class="col-sm-9">
                    <p>{{ $estabelecimento->CODIGO_ESTABELECIMENTO }}</p>
                  </div>

                  <div id="{{ "divMCap".$estabelecimento->CODIGO }}" class="col-sm-3">
                    <input id="{{ "inputMeioCap-".$estabelecimento->CODIGO }}" class="checkCodEstabelecimento" value="{{ $estabelecimento->CODIGO }}" name="arrayMeioCaptura[]" type="checkbox">
                  </div>
                  <hr>
                  @endforeach
                  @endif
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="addSelecionadosMeioCaptura({{$cod_estabelecimento}})"><b>Confirmar</b></button>
              </div>
            </div>
          </div>
        </div>
      </form>

      <div id="resultadosPesquisa">
        <div class="row">
          <div class="col-md-6 col-lg-2">
            <div class="card report-card">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-8">
                    <p class="text-dark font-weight-semibold font-12">QTD</p>
                    <h6 id="total_registros" class="my-3"></h6>
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img src="{{ url('assets/images/vendasoperadora/quantidade.png')}}" alt="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-2">
            <div class="card report-card">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-8">
                    <p class="text-dark font-weight-semibold font-12">BRUTO</p>
                    <h6 id="total_bruto_vendas" class="my-3"></h6>
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img src="{{ url('assets/images/vendasoperadora/bruto.png')}}" alt="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-2">
            <div class="card report-card">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-8">
                    <p class="text-dark font-weight-semibold font-12">VALOR TAXA</p>
                    <h6 id="total_taxa_cobrada" class="my-3 text-danger"></h6>
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img src="{{ url('assets/images/vendasoperadora/percentagem.png')}}" alt="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-2">
            <div class="card report-card">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-8">
                    <p class="text-dark font-weight-semibold font-12">TARIFA MÍNIMA</p>
                    <h6 id="total_taxa_minima" class="my-3 text-danger"></h6>
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img src="{{ url('assets/images/vendasoperadora/percentagem.png')}}" alt="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card report-card">
              <div class="card-body">
                <div class="row d-flex justify-content-center">
                  <div class="col-8">
                    <p class="text-dark font-weight-semibold font-12">VALOR LÍQUIDO DE VENDAS</p>
                    <h6 id="total_liquido_vendas" class="my-3"></h6>
                  </div>
                  <div class="col-4 align-self-center">
                    <div class="report-main-icon bg-light-alt">
                      <img src="{{ url('assets/images/vendasoperadora/liquido.png')}}" alt="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row" id="foo">
          <div  class="col-sm-2">

          </div>

          <div class="col-sm-10" align="right">
            <div class="dropdown">
              <a class="btn btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-file-download"></i> <b>Exportar </b>
              </a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" id="dp-item"  href="{{ action('VendasController@downloadTable') }}">  PDF</a>
                <!-- <a class="dropdown-item" id="btExportXls"  onclick="exportTableToExcel('#jsgrid-table', '#btExportXls')" href="#">  XLS (EXCEL)</a> -->
                <a class="dropdown-item" id="btExportXls"  onclick="exportXls()">  XLS (EXCEL)</a>
              </div>
            </div>
            <span id="label-gerando-xls"> Gerando XLS </span>
          </div>
        </div><br>

        <div class="modal fade" id="modal-cupom" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalCupom" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalCupom">Comprovante</h5>
              </div>
              <div class="modal-body">
                <table>
                  <tbody>
                    <thead>
                      <h4 id="moda_titulo">  </h4>
                      <h6 id="modal_cnpj"> CNPJ: {{ $venda->CNPJ}}</h6>
                      <h6 id="modal_empresa"> CNPJ: {{ $venda->CNPJ}}</h6>
                      <h6 style="margin-top: -15px">--------------------------------------</h6>
                      <div class="body-cupom">
                        <h6 id="modal_data">  </h6>
                        <h6 id="modal_operadora">  </h6>
                        <h6 id="modal_bandeira">  </h6>
                        <h6 id="modal_forma_pagamento">  </h6>
                        <h6 id="modal_estabelecimento">  </h6>
                        <h6 id="modal_cartao">  </h6>
                        <h6 id="modal_valor_bruto">  </h6>
                        <h6 id="modal_data_previsao"></h6>
                      </div>
                    </thead>
                  </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Fechar</b></button>
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="imprimeCupom()"><b>Imprimir</b></button>
              </div>
            </div>
          </div>
        </div>

        <div class="table-scroll">
          <table id="jsgrid-table" class="table sortable">
            <thead>
              <tr>
                <th> Detalhes </th>
                <th> Empresa  <br> <input name="EMPRESA" onkeypress="filtraTabela('EMPRESA', event)" ></th>
                <th> CNPJ  <br> <input name="CNPJ" onkeypress="filtraTabela('CNPJ', event)"> </th>
                <th> Operadora<br> <input name="ADQUIRENTE" onkeypress="filtraTabela('ADQUIRENTE', event)"> </th>
                <th> Venda  <br> <input name="DATA_VENDA" onkeypress="filtraTabela('DATA_VENDA', event)"></th>
                <th> Previsão <br> <input name="DATA_PREVISTA_PAGTO" onkeypress="filtraTabela('DATA_PREVISTA_PAGTO', event)"> </th>
                <th> Bandeira<br> <input name="BANDEIRA" onkeypress="filtraTabela('BANDEIRA', event)"> </th>
                <th> Forma de Pagamento<br> <input> </th>
                <th> NSU <br> <input name="NSU" onkeypress="filtraTabela('NSU', event)"></th>
                <th> Autorização <br> <input name="AUTORIZACAO" onkeypress="filtraTabela('AUTORIZACAO', event)"></th>
                <th> Cartão<br> <input name="CARTAO" onkeypress="filtraTabela('CARTAO', event)"> </th>
                <th> Valor Bruto <br> <input name="VALOR_BRUTO" onkeypress="filtraTabela('VALOR_BRUTO', event)"></th>
                <th> Taxa %  <br> <input name="PERCENTUAL_TAXA" onkeypress="filtraTabela('PERCENTUAL_TAXA', event)"></th>
                <th> Taxa R$ <br> <input name="VALOR_TAXA" onkeypress="filtraTabela('VALOR_TAXA', event)"></th>
                <th> Tarifa Mínima R$ <br> <input name="TAXA_MINIMA" onkeypress="filtraTabela('VALOR_TAXA', event)"></th>
                <th> Outras Tarifas<br> <input name="OUTRAS_DESPESAS" onkeypress="filtraTabela('OUTRAS_DESPESAS', event)"> </th>
                <th> Valor Líquido <br> <input name="VALOR_LIQUIDO" onkeypress="filtraTabela('VALOR_LIQUIDO', event)"> </th>
                <th> Parcela <br> <input name="PARCELA" onkeypress="filtraTabela('PARCELA', event)"></th>
                <th> Total Parc. <br> <input name="TOTAL_PARCELAS" onkeypress="filtraTabela('TOTAL_PARCELAS', event)"></th>
                <th> Hora <br> <input name="HORA_TRANSACAO" onkeypress="filtraTabela('HORA_TRANSACAO', event)"></th>
                <th> Estabelecimento<br> <input name="ESTABELECIMENTO" onkeypress="filtraTabela('ESTABELECIMENTO', event)"> </th>
                <th> Banco<br> <input > </th>
                <th> Agência<br> <input name="AGENCIA" onkeypress="filtraTabela('AGENCIA', event)"> </th>
                <th> Conta <br> <input name="CONTA" onkeypress="filtraTabela('CONTA', event)"></th>
                <th> Observação <br> <input name="OBSERVACAO" onkeypress="filtraTabela('OBSERVACAO', event)"></th>
                <th> Produto<br> <input> </th>
                <th> Meio de Captura<br> <input> </th>
                <th> Status Conciliação<br> <input> </th>
                <th> Status Financeiro<br> <input> </th>
                <th> Justificativa <br> <input name="JUSTIFICATIVA" onkeypress="filtraTabela('JUSTIFICATIVA', event)"> </th>
                <th> RO <br> <input></th>
                <th> RO Único <br> <input></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <td style='color:#6E6E6E; font-weight: bolder'> Totais </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td style='color:#6E6E6E' id="valor_bruto"> </td>
              <td> </td>
              <td  style='color:red' id="valor_taxa"> </td>
              <td  style='color:red' id="outras_tarifas"> </td>
              <td  style='color:#6E6E6E' id="valor_liquido"> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>
            </tfoot>
          </table>
        </div>

        <div class="table-scroll xls">
          <table id="table-xls" class="table">
            <thead>
              <tr>
                <th> Empresa </th>
                <th> CNPJ  </th>
                <th> Operadora </th>
                <th> Venda </th>
                <th> Previsão </th>
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
                <th> Banco </th>
                <th> Agência </th>
                <th> Conta</th>
                <th> Observação</th>
                <th> Produto </th>
                <th> Meio de Captur </th>
                <th> Status Conciliaçã </th>
                <th> Status Financeir </th>
                <th> Justificativa </th>
                <th> RO</th>
                <th> RO Único</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-between align-items-end flex-wrap">
          <nav aria-label="Page navigation example">
            <ul id="ul_pagination" class="pagination">
            </ul>
          </nav>

          <div class="form-group">
            <label for="quantidadePorPagina">Quantidade por página</label>
            <select onchange="novaQuantidadePagina()" name="porPagina" id="quantidadePorPagina" class="form-control">
              <option value="10" selected>10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <option value="200">200</option>
            </select>
          </div>
        </div>
      </div>
      <br>
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
<script src="{{ URL::asset('plugins/datatables/pdfmake.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.colVis.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/vendas/export-excel-vendas.js')}}"></script>
<!-- <script src="{{ URL::asset('assets/js/vendas/vendas-operadora-sort.js')}}"></script> -->
<!-- Responsive examples -->
<script src="{{ URL::asset('plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ URL::asset('assets/pages/jquery.datatable.init.js')}}"></script>


@stop

<script>

let filtros_formulario_principal = {};

$('#submitFormLogin').click(function(){
  document.getElementById("preloader").style.display = "block";

  $('#jsgrid-table tbody').empty();

  let array = [];
  let arrayAdquirentes = [];
  let arrayBandeira = [];
  let arrayModalidade = [];
  let arrayStatusFinanceiro = [];
  let arrayStatusConciliacao = [];
  let arrayCodEstabelecimento = [];

  const checkboxEmpresa = document.querySelectorAll('input[type=checkbox]:checked.checkEmpresa');
  const checkboxAdquirentes = document.querySelectorAll('input[type=checkbox]:checked.checkAdquirentes');
  const checkboxBandeira = document.querySelectorAll('input[type=checkbox]:checked.checkBandeira');
  const checkboxModalidade = document.querySelectorAll('input[type=checkbox]:checked.checkModalidade');
  const checkboxCodEstabelecimento = document.querySelectorAll('input[type=checkbox]:checked.checkCodEstabelecimento');
  const checkboxStatusConciliacao = document.querySelectorAll('input[type=checkbox]:checked.checkStatusConciliacao');
  const data_inicial = document.getElementById("date_inicial").value;
  const data_final = document.getElementById("date_final").value;

  if(document.getElementById("pendente").checked) { arrayStatusFinanceiro.push(1); }
  if(document.getElementById("liquidado").checked){ arrayStatusFinanceiro.push(2); }
  if(document.getElementById("cancelada").checked){ arrayStatusFinanceiro.push(3); }

  checkboxEmpresa.forEach((checkEmpresa) => { array.push(checkEmpresa.defaultValue) });
  checkboxAdquirentes.forEach((checkAdquirente) => { arrayAdquirentes.push(checkAdquirente.defaultValue) });
  checkboxBandeira.forEach((checkBandeira) => { arrayBandeira.push(checkBandeira.defaultValue) });
  checkboxModalidade.forEach((checkModalidade) => { arrayModalidade.push(checkModalidade.defaultValue) });
  checkboxStatusConciliacao.forEach((checkStatusConciliacao) => { arrayStatusConciliacao.push(checkStatusConciliacao.defaultValue) });
  checkboxCodEstabelecimento.forEach((checkCodEstabelecimento) => { arrayCodEstabelecimento.push(checkCodEstabelecimento.defaultValue) });

  const qtdeVisivelInicial = 10;
  const filtro_tabela = null;
  const valor_digitado = null;

  const dados = {
    data_inicial,
    data_final,
    array,
    arrayAdquirentes,
    arrayBandeira,
    arrayModalidade,
    arrayStatusConciliacao,
    arrayStatusFinanceiro,
    arrayCodEstabelecimento,
    qtdeVisivelInicial,
    filtro_tabela,
    valor_digitado
  }

  filtros_formulario_principal = dados;

  let dados_filtro = JSON.parse(JSON.stringify(dados));
  let filtro = JSON.stringify(dados);

  localStorage.setItem('dados_filtro', filtro);

  $('#jsgrid-table tbody').empty();
  $('#ul_pagination li').empty();

  $.ajax({
    url: "{{ url('vendasoperadorasfiltro') }}",
    type: "POST",
    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: dados_filtro,
    dataType: 'json',
    success: function (response){
      if(response) {

        renderizaTabela(response);
        atualizaBoxes(response);
        atualizaSubTotais(response);
        renderizaPaginacao(response, filtro);

        document.getElementById("preloader").style.display = "none";
        window.scrollTo(0, 550);
      }
    }
  });
});

function paginate(paginada_selecionada, dados){
  dados.qtdeVisivelInicial = document.getElementById("quantidadePorPagina").value;
  document.getElementById("quantidadePorPagina").selected;

  const filtro = JSON.stringify(dados);

  document.getElementById("preloader").style.display = "block";

  $('#jsgrid-table tbody').empty();
  $('#ul_pagination li').empty();

  $.ajax({
    url: "{{ url('vendasoperadorasfiltro') }}" + "?page=" + paginada_selecionada,
    type: "post",
    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: dados,
    dataType: 'json',
    success: function (response){
      if(response){

        renderizaTabela(response);
        atualizaSubTotais(response);
        renderizaPaginacaoPaginate(response, filtro);

        document.getElementById("preloader").style.display = "none";
      }
    }
  });
}

var empresasSelecionadas = [];
var adquirentesSelecionados = [];
var bandeirasSelecionados = [];
var modalidadesSelecionados = [];
var meioCapturaSelecionados = [];

function submit(){
  document.getElementById("preloader").style.display = "block";
  document.getElementById("preloader").style.opacity = 0.9;

  setTimeout(function () {
    document.getElementById("myform").submit();
  },200)
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
    if(document.getElementById("inputAdq-"+adquirente.CODIGO).checked){
      adquirentesSelecionados.includes(adquirente.ADQUIRENTE) ? '' : adquirentesSelecionados.push(adquirente.ADQUIRENTE);
    }else{
      adquirentesSelecionados.includes("inputAdq-"+adquirente.ADQUIRENTE) ? adquirentesSelecionados.splice(adquirentesSelecionados.indexOf(adquirente.ADQUIRENTE), 1) : '';
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

function addSelecionadosMeioCaptura(cod_estabelecimento){
  cod_estabelecimento.forEach((cod_estabelecimento) => {
    if(document.getElementById("inputMeioCap-"+cod_estabelecimento.CODIGO).checked){
      meioCapturaSelecionados.includes(cod_estabelecimento.CODIGO_ESTABELECIMENTO) ? '' :  meioCapturaSelecionados.push(cod_estabelecimento.CODIGO_ESTABELECIMENTO);
    }else{
      meioCapturaSelecionados.includes(cod_estabelecimento.CODIGO_ESTABELECIMENTO) ? meioCapturaSelecionados.splice(meioCapturaSelecionados.indexOf(cod_estabelecimento.CODIGO_ESTABELECIMENTO), 1) : '';
    }
  });

  document.getElementById("codestabelecimento").value = meioCapturaSelecionados;
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

        const regex = new RegExp(val_input);
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

function filtroNomeAdquirente(adquirentes) {
  setTimeout(function () {
    var val_input = document.getElementById("inputAdq").value;

    if(val_input == ""){
      adquirentes.forEach((adq) => {
        document.getElementById(adq.ADQUIRENTE).style.display = "block";
        document.getElementById("divAdq"+adq.CODIGO).style.display = "block";

      });
    }else {
      adquirentes.forEach((adq) => {

        const regex = new RegExp(val_input, 'gi');
        resultado = adq.ADQUIRENTE.match(regex);

        if(resultado) {
          document.getElementById(adq.ADQUIRENTE).style.display = "block";
          document.getElementById("divAdq"+adq.CODIGO).style.display = "block";
        }else{
          document.getElementById(adq.ADQUIRENTE).style.display = "none";
          document.getElementById("divAdq"+adq.CODIGO).style.display = "none";
        }
      });
    }
  },300)
}

function filtroNomeBandeira(bandeiras){
  setTimeout(function () {
    var val_input = document.getElementById("inputBad").value;

    if(val_input == ""){
      bandeiras.forEach((bandeira) => {
        document.getElementById(bandeira.BANDEIRA).style.display = "block";
        document.getElementById("divBad"+bandeira.CODIGO).style.display = "block";
      });
    }else{
      bandeiras.forEach((bandeira) => {

        var regex = new RegExp(val_input, 'gi');
        resultado = bandeira.BANDEIRA.match(regex);

        if(resultado) {
          document.getElementById(bandeira.BANDEIRA).style.display = "block";
          document.getElementById("divBad"+bandeira.CODIGO).style.display = "block";
        }else{
          document.getElementById(bandeira.BANDEIRA).style.display = "none";
          document.getElementById("divBad"+bandeira.CODIGO).style.display = "none";
        }
      });
    }
  },300)
}

function filtroNomeModalidade(modalidades){
  setTimeout(function () {
    var val_input = document.getElementById("ftModalidade").value;

    if(val_input == ""){
      modalidades.forEach((cliente) => {
        document.getElementById(cliente.DESCRICAO).style.display = "block";
        // document.getElementById(cliente.CNPJ).style.display = "block";
        document.getElementById("divCod"+cliente.CODIGO).style.display = "block";

      });
    }else{
      modalidades.forEach((cliente) => {

        var regex = new RegExp(val_input, 'gi');

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

function filtroCodEstabelecimento(grupo_clientes){

  setTimeout(function () {
    var val_input = document.getElementById("inputCodEstabelecimento").value.toUpperCase();

    if(val_input == ""){
      grupo_clientes.forEach((cliente) => {
        document.getElementById(cliente.CODIGO_ESTABELECIMENTO).style.display = "block";
        document.getElementById(cliente.ADQUIRENTE).style.display = "block";
        document.getElementById(cliente.NOME_EMPRESA).style.display = "block";

        document.getElementById("divMCap"+cliente.CODIGO).style.display = "block";

      });
    }else{
      grupo_clientes.forEach((cliente) => {

        var regex = new RegExp(val_input);

        resultado = cliente.CODIGO_ESTABELECIMENTO.match(regex);

        if(resultado) {
          document.getElementById(cliente.CODIGO_ESTABELECIMENTO).style.display = "block";
          document.getElementById("divMCap"+cliente.CODIGO).style.display = "block";
        }else{
          document.getElementById(cliente.CODIGO_ESTABELECIMENTO).style.display = "none";
          document.getElementById("divMCap"+cliente.CODIGO).style.display = "none";
        }
      });
    }
  },300)
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

function allCheckboxAd(grupo_clientes) {
  grupo_clientes.forEach((cliente) => {
    if(document.getElementById("allCheckAd").checked){
      document.getElementById("inputAdq-"+cliente.CODIGO).checked = true;
    }else{
      document.getElementById("inputAdq-"+cliente.CODIGO).checked = false;
    }
  });
}

function allCheckboxBandeira(grupo_clientes) {
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
    if(document.getElementById("allCheckCodEstabelecimento").checked){
      document.getElementById("inputMeioCap-"+cliente.CODIGO).checked = true;
    }else{
      document.getElementById("inputMeioCap-"+cliente.CODIGO).checked = false;
    }
  });
}

function limparFiltros() { document.getElementById("myform").reset(); }

function mudaCorLinhaTable(codigo){
  var cor = document.getElementById(codigo).style.background;

  if(cor == "" || cor == "rgb(255, 255, 255)") {
    var cor = document.getElementById(codigo).style.background = "#A4A4A4";
    var cor = document.getElementById(codigo).style.color = "#ffffff";
  } else {
    document.getElementById(codigo).style = "background: #ffffff; color: #231F20";
  }
}

function desfazerConciliacao(codigo){
  console.log(codigo);
}

function desfazerJustificativa(codigo){
  const url = "/desfazer-justificativa/" + codigo;

  $.ajax({
    url: url,
    type: "GET",
    dataType: "json",
    success: function(response){
      console.log(response);
    }
  })
}

function novaQuantidadePagina(qtde_pagina) {
  let array = [];
  let arrayAdquirentes = [];
  let arrayBandeira = [];
  let arrayModalidade = [];
  let arrayMeioCaptura = [];
  let arrayStatusFinanceiro = [];
  let arrayStatusConciliacao = [];

  const qtdeVisivelInicial = document.getElementById("quantidadePorPagina").value;
  const checkboxEmpresa = document.querySelectorAll('input[type=checkbox]:checked.checkEmpresa');
  const checkboxAdquirentes = document.querySelectorAll('input[type=checkbox]:checked.checkAdquirentes');
  const checkboxBandeira = document.querySelectorAll('input[type=checkbox]:checked.checkBandeira');
  const checkboxModalidade = document.querySelectorAll('input[type=checkbox]:checked.checkModalidade');
  const checkboxMeioCaptura = document.querySelectorAll('input[type=checkbox]:checked.checkMeioCaptura');
  const checkboxStatusConciliacao = document.querySelectorAll('input[type=checkbox]:checked.checkStatusConciliacao');
  const data_inicial = document.getElementById("date_inicial").value;
  const data_final = document.getElementById("date_final").value;

  if(document.getElementById("pendente").checked) { arrayStatusFinanceiro.push(1); }
  if(document.getElementById("liquidado").checked){ arrayStatusFinanceiro.push(2); }
  if(document.getElementById("cancelada").checked){ arrayStatusFinanceiro.push(3); }

  checkboxEmpresa.forEach((checkEmpresa) => { array.push(checkEmpresa.defaultValue) });
  checkboxAdquirentes.forEach((checkAdquirente) => { arrayAdquirentes.push(checkAdquirente.defaultValue) });
  checkboxBandeira.forEach((checkBandeira) => { arrayBandeira.push(checkBandeira.defaultValue) });
  checkboxModalidade.forEach((checkModalidade) => { arrayModalidade.push(checkModalidade.defaultValue) });
  checkboxStatusConciliacao.forEach((checkStatusConciliacao) => { arrayStatusConciliacao.push(checkStatusConciliacao.defaultValue) });

  const dados = {
    data_inicial,
    data_final,
    array,
    arrayAdquirentes,
    arrayBandeira,
    arrayModalidade,
    arrayStatusConciliacao,
    arrayStatusFinanceiro,
    arrayMeioCaptura,
    qtdeVisivelInicial
  }

  paginate(1, dados)
}

function exibeModal(venda){
  localStorage.setItem('codigo_cupom_venda', venda.CODIGO);

  const data_venda = formataData(venda.DATA_VENDA);
  const data_prev_pag = formataData(venda.DATA_PREVISTA_PAGTO);
  const valor_bruto = formataValorReal(venda.VALOR_BRUTO);

  document.getElementById("modal_data").innerHTML = "DATA VENDA " + data_venda;
  document.getElementById("modal_cnpj").innerHTML = "CNPJ " + venda.CNPJ;
  document.getElementById("modal_empresa").innerHTML = "EMPRESA " + venda.EMPRESA;
  document.getElementById("modal_cartao").innerHTML = "CARTÃO " + venda.CARTAO
  document.getElementById("modal_bandeira").innerHTML = "BANDEIRA " + venda.BANDEIRA;
  document.getElementById("modal_operadora").innerHTML = "OPERADORA " + venda.ADQUIRENTE;
  document.getElementById("modal_operadora").innerHTML = "FORMA DE PAGAMENTO " + venda.DESCRICAO;
  document.getElementById("modal_valor_bruto").innerHTML = "VALOR: " + valor_bruto;
  document.getElementById("modal_data_previsao").innerHTML = "PREVISÂO DE PAGAMENTO " + data_prev_pag;

  $("#modal-cupom").modal({
    show: true
  });
}

function imprimeCupom(){
  let btn = document.createElement('a');
  btn.href = "{{ url('/impressao-vendas')}}"+"/"+localStorage.getItem('codigo_cupom_venda');
  btn.target = "_blank"
  btn.click();
}

function exportXls(){

  const dados_filtro =  JSON.parse(localStorage.getItem('dados_filtro'));

  document.getElementById("label-gerando-xls").style.display = "block";
  $.ajax({
    url: "{{ url('exportxls-vendas-operadora') }}",
    type: "POST",
    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: dados_filtro,
    dataType: 'json',
    success: function (response){
      if(response){
        for(var i=0;i< response.length; i++){

          const dados_cupom   = JSON.stringify(response[i]);
          const data_venda    = formataData(response[i].DATA_VENDA);
          const data_prev_pag = formataData(response[i].DATA_PREVISTA_PAGTO);

          const formatted     = formataValorReal(response[i].VALOR_BRUTO);
          const formatted_liq = formataValorReal(response[i].VALOR_LIQUIDO);
          const formatted_tx  = formataValorReal(response[i].VALOR_TAXA);
          const formatted_outras_despesas = formataValorReal(response[i].OUTRAS_DESPESAS);

          const a = response[i].PERCENTUAL_TAXA;
          const val_taxa = Number(a).toFixed(2);
          let html = "<tr>";

          html +="<td>"+response[i].EMPRESA+"</td>";
          html +="<td>"+response[i].CNPJ+"</td>";
          html +="<td>"+ response[i].ADQUIRENTE+"</td>";
          html +="<td>"+data_venda+"</td>";
          html +="<td>"+data_prev_pag+"</td>";
          html +="<td>"+response[i].BANDEIRA+"</td>";
          html +="<td>"+response[i].DESCRICAO+"</td>";
          html +="<td>"+response[i].NSU+"</td>";
          html +="<td>"+response[i].AUTORIZACAO+"</td>";
          html +="<td>"+response[i].CARTAO+"</td>";
          html +="<td>"+formatted +"</td>";
          html +="<td>"+val_taxa+"</td>";
          html +="<td style='color:red'>"+formatted_tx+"</td>";
          html +="<td>"+formatted_outras_despesas+"</td>";
          html +="<td>"+formatted_liq+"</td>";
          html +="<td>"+response[i].PARCELA+"</td>";
          html +="<td>"+response[i].TOTAL_PARCELAS+"</td>";
          html +="<td>"+response[i].HORA_TRANSACAO+"</td>";
          html +="<td>"+response[i].ESTABELECIMENTO+"</td>";
          html +="<td>"+response[i].BANCO+"</td>";
          html +="<td>"+ `${response[i].AGENCIA || ''}` +"</td>";
          html +="<td>"+ `${response[i].CONTA || ''}` +"</td>";
          html +="<td>"+ `${response[i].OBSERVACOES || ''}` +"</td>";
          if(response[i].COD_PRODUTO !=  null){
            html +="<td>"+response[i].PRODUTO_WEB+"</td>";
          }else{
            html +="<td>"+""+"</td>";
          }
          html +="<td>"+response[i].MEIOCAPTURA+"</td>";
          html +="<td>"+response[i].status_conc+"</td>";
          html +="<td>"+response[i].status_finan+"</td>";
          html +="<td>"+""+"</td>";
          html +="<td>"+""+"</td>";
          html +="<td>"+""+"</td>";
          html +="</tr>";

          $('#table-xls').append(html);
        }

        $('#table-xls').DataTable( {
          dom: 'Bfrtip',
          buttons: [
            'excel'
          ]
        } );

        $(".buttons-excel").trigger("click");
        document.getElementById("label-gerando-xls").style.display = "none";
      }
    }
  });
}

function formataData(data){
  const new_data_ = new Date(data);
  const data_formatada = new_data_.toLocaleDateString('pt-BR', {timeZone: 'UTC'});

  return data_formatada;
}

function formataValorReal(valor){
  const formatter = new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  });
  const valor_formatado = formatter.format(valor);

  return valor_formatado;
}

function filtraTabela(campo, event){
  if(event.keyCode == 13) {

    $('#jsgrid-table tbody').empty();
    $('#ul_pagination li').empty();

    let valor_digitado = document.querySelector(`input[name=${campo}]`).value;

    filtros_formulario_principal['filtro_tabela'] = campo;
    filtros_formulario_principal['valor_digitado'] = valor_digitado;

    let dados_filtro = JSON.parse(JSON.stringify(filtros_formulario_principal));
    let filtro = JSON.stringify(filtros_formulario_principal);

    $.ajax({
      url: "{{ url('vendasoperadorasfiltro') }}",
      type: "POST",
      headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data: dados_filtro,
      dataType: 'json',
      success: function (response){
        if(response){

          renderizaTabela(response);
          atualizaSubTotais(response);
          renderizaPaginacao(response, filtro);

          window.scrollTo(0, 770);

          document.getElementById("preloader").style.display = "none";
        }
      }
    });
  }
}

function renderizaPaginacao(response, filtro){
  let li_html = "<li><a>" + "" + "</a></li>"

  if(response[0].last_page < 10){
    for(let i=1; i<=response[0].last_page; i++){
      if(i == response[0].current_page){
        li_html += "<li class='page-item active'><a class='page-link' onclick='paginate("+i+","+filtro+")'>" + i + "</a></li>"
      }else{
        li_html += "<li class='page-item'><a class='page-link' onclick='paginate("+i+","+filtro+")'>" + i + "</a></li>"
      }
    }
  }else{
    for(let i=1; i<=5; i++){
      if(i == response[0].current_page){
        li_html += "<li class='page-item active'><a class='page-link' onclick='paginate("+i+","+ filtro + ")'>" + i + "</a></li>"
      }else{
        li_html += "<li class='page-item'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"
      }
    }
    li_html += "<li class='page-item'><a class='page-link'>" + "..." + "</a></li>"
    for(let i=response[0].last_page-2; i<=response[0].last_page; i++){
      li_html += "<li class='page-item'><a class='page-link' onclick='paginate("+i+","+ filtro + ")'>" + i + "</a></li>"
    }
  }

  $('#ul_pagination').append(li_html);
}

function renderizaPaginacaoPaginate(response, filtro){
  let li_html = "<li><a>" + "" + "</a></li>"

  if(response[0].last_page < 10){
    for(let i=1; i<=response[0].last_page; i++){
      if(i == response[0].current_page){
        li_html += "<li class='page-item active'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"
      }else{
        li_html += "<li class='page-item'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"
      }
    }
  }else if(response[0].current_page >=5 && response[0].current_page < response[0].last_page-2){
    for(let i=1; i<3; i++){
      li_html += "<li class='page-item'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"
    }
    li_html += "<li class='page-item'><a class='page-link'>" + "..." + "</a></li>"
    for(let i=response[0].current_page-1; i<response[0].current_page+2; i++){
      if(i == response[0].current_page){
        li_html += "<li class='page-item active'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"
      }else{
        li_html += "<li class='page-item'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"
      }
    }
    li_html += "<li class='page-item'><a class='page-link'>" + "..." + "</a></li>"
    for(let i=response[0].last_page-2; i<=response[0].last_page; i++){
      li_html += "<li class='page-item'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"

    }
  }else if(response[0].current_page >= response[0].last_page-2){
    for(let i=1; i<3; i++){
      li_html += "<li class='page-item'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"
    }
    li_html += "<li class='page-item'><a class='page-link'>" + "..." + "</a></li>"
    for(let i=response[0].last_page-2; i<=response[0].last_page; i++){
      if(i == response[0].current_page){
        li_html += "<li class='page-item active'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"
      }else{
        li_html += "<li class='page-item'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"
      }
    }
  }else{
    for(let i=1; i<=5; i++){
      if(i == response[0].current_page){
        li_html += "<li class='page-item active'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"
      }else{
        li_html += "<li class='page-item'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"
      }
    }
    li_html += "<li class='page-item'><a class='page-link'>" + "..." + "</a></li>"
    for(let i =response[0].last_page-2; i<=response[0].last_page; i++){
      li_html += "<li class='page-item'><a class='page-link' onclick='paginate("+i+","+  filtro + ")'>" + i + "</a></li>"
    }
  }

  $('#ul_pagination').append(li_html);
}

function renderizaTabela(response){
  for(var i=0;i< response[0].data.length; i++){

    let dados_cupom   = JSON.stringify(response[0].data[i]);
    let data_venda    = formataData(response[0].data[i].DATA_VENDA);
    let data_prev_pag = formataData(response[0].data[i].DATA_PREVISTA_PAGTO);

    let formatted                 = formataValorReal(response[0].data[i].VALOR_BRUTO);
    let formatted_liq             = formataValorReal(response[0].data[i].VALOR_LIQUIDO);
    let formatted_tx              = formataValorReal(response[0].data[i].VALOR_TAXA);
    let formatted_outras_despesas = formataValorReal(response[0].data[i].OUTRAS_DESPESAS);

    let cod      = response[0].data[i].COD;
    let val_taxa = Number(response[0].data[i].PERCENTUAL_TAXA).toFixed(2);

    let html = "<tr id='"+cod+"' onclick='mudaCorLinhaTable("+cod+")'>";

    if(response[0].data[i].COD_STATUS_CONCILIACAO == 6) {
      html +="<td>" + "<a href='' data-toggle='tooltip' data-placement='bottom' title='Desfazer Conciliação' onclick='desfazerConciliacao(" + response[0].data[i].CODIGO + ")'><i style='font-size: 17px' class='fas fa-undo-alt'></i></a>"+" "+
      "<a href='{{ url('/impressao-vendas')}}"+"/"+response[0].data[i].COD+"' data-toggle='tooltip' data-placement='bottom' title='Visualiza Comprovante' target='_blank'><i style='font-size: 17px' class='fas fa-print'></i></a>"+"</td>";
    }else if(response[0].data[i].COD_STATUS_CONCILIACAO == 3){
      html +="<td>" + "<a href='' data-toggle='tooltip' data-placement='bottom' title='Desfazer Justificativa' onclick='desfazerJustificativa(" + response[0].data[i].CODIGO + ")'><i style='font-size: 17px' class='fas fa-history'></i></a>"+" "+
      "<a href='{{ url('/impressao-vendas')}}"+"/"+response[0].data[i].COD+"' data-toggle='tooltip' data-placement='bottom' title='Visualiza Comprovante' target='_blank'><i style='font-size: 17px' class='fas fa-print'></i></a>"+"</td>";
    }else{
      html += "<td>" + "<a onclick='exibeModal("+dados_cupom+")'  data-target='#staticBackdrop' data-placement='bottom' title='Visualiza Comprovante'><i style='font-size: 17px' class='fas fa-print'></i></a>"+"</td>";
    }

    html +="<td>"+response[0].data[i].EMPRESA+"</td>";
    html +="<td>"+response[0].data[i].CNPJ+"</td>";
    html +="<td>"+"<img src='" + `${response[0].data[i].IMAGEMAD || 'assets/images/iconCart.jpeg'}` +"'' style='width: 30px'/>"+"</td>";
    html +="<td>"+data_venda+"</td>";
    html +="<td>"+data_prev_pag+"</td>";
    html +="<td>"+"<img src='" + `${response[0].data[i].IMAGEMBAD || 'assets/images/iconCart.jpeg'}` +"'' style='width: 30px'/>"+"</td>";
    html +="<td>"+response[0].data[i].DESCRICAO+"</td>";
    html +="<td>"+response[0].data[i].NSU+"</td>";
    html +="<td>"+response[0].data[i].AUTORIZACAO+"</td>";
    html +="<td>"+response[0].data[i].CARTAO+"</td>";
    html +="<td>"+formatted +"</td>";
    html +="<td>"+val_taxa+"</td>";
    html +="<td style='color:red'>"+formatted_tx+"</td>";
    html +="<td>"+response[7]+"</td>";
    html +="<td>"+formatted_outras_despesas+"</td>";
    html +="<td>"+formatted_liq+"</td>";
    html +="<td>"+response[0].data[i].PARCELA+"</td>";
    html +="<td>"+response[0].data[i].TOTAL_PARCELAS+"</td>";
    html +="<td>"+response[0].data[i].HORA_TRANSACAO+"</td>";
    html +="<td>"+response[0].data[i].ESTABELECIMENTO+"</td>";
    html +="<td>"+"<img src='" + `${response[0].data[i].IMAGEM_LINK || 'assets/images/iconCart.jpeg'}` +"'' style='width: 30px'/>"+"</td>";
    html +="<td>"+ `${response[0].data[i].AGENCIA || ''}` +"</td>";
    html +="<td>"+ `${response[0].data[i].CONTA || ''}` +"</td>";
    html +="<td>"+ `${response[0].data[i].OBSERVACOES || ''}` +"</td>";
    html +="<td>"+ `${response[0].data[i].PRODUTO_WEB || ''}` +"</td>";
    html +="<td>"+response[0].data[i].MEIOCAPTURA+"</td>";
    html +="<td>"+response[0].data[i].status_conc+"</td>";
    html +="<td>"+response[0].data[i].status_finan+"</td>";
    html +="<td>"+`${response[0].data[i].JUSTIFICATIVA || ''}`+"</td>";
    html +="<td>"+""+"</td>";
    html +="<td>"+""+"</td>";
    html +="</tr>";
    $('#jsgrid-table').append(html);
  }
}

function atualizaSubTotais(response){
  document.getElementById("total_liquido_vendas").innerHTML = "R$ "+response[1];
  document.getElementById("total_registros").innerHTML = response[3];
  document.getElementById("total_taxa_cobrada").innerHTML = "R$ -"+response[4];
  document.getElementById("total_bruto_vendas").innerHTML = "R$ "+response[2];
  document.getElementById("total_taxa_minima").innerHTML = response[7];
}

function atualizaBoxes(response){
  document.getElementById("valor_bruto").innerHTML = response[2];
  document.getElementById("valor_liquido").innerHTML = response[1];
  document.getElementById("valor_taxa").innerHTML = response[4];
  document.getElementById("outras_tarifas").innerHTML = response[6];
  document.getElementById("resultadosPesquisa").style.display = "block";
}

</script>

@stop
