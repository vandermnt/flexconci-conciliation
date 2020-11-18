@extends('layouts.analytics-master')

@section('title', 'Metrica - Admin & Dashboard Template')

@section('headerStyle')
  <link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
  <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/vendas/pagina-vendaserp.css')}}" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>

  <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <!-- Responsive datatable examples -->
  <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
@stop

@section('content')
  <div id="preloader" class="loader hidden"></div>

  <div id="tudo_page" class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        @component('common-components.breadcrumb')
        @slot('title') Vendas ERP @endslot
        @slot('item1') Vendas @endslot
        <!-- @slot('item2') Antecipação de Venda @endslot -->
        @endcomponent
      </div>
    </div>
    <form id="myform" action="{{ action('VendasErpController@buscarVendasErp') }}" method="post">
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
                          <input class="form-control" type="date" id="date_inicial" value="{{  date("Y-m-01")}}" name="data_inicial">
                        </div>
                        <div class="col-sm-6">
                          <h6> Data Final: </h6>
                          <input class="form-control" type="date" id="date_final" value="{{ date("Y-m-d") }}" name="data_final">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div id="filtroempresa">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">
                          <h6> Adquirente: </h6>
                          <input id="adquirente" class="adquirente form-control" name="adquirente">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-2">
                  <button id="bt-selecionar-adquirentes" type="button" class="btn btn-sm bt-filtro-selecao" data-toggle="modal" data-target="#staticBackdropAdquirente">
                    <b>Selecionar</b>
                  </button>
                </div>

                <div class="col-sm-6">
                  <div id="filtroempresa">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">
                          <h6> Meio de Captura: </h6>
                          <input id="meiocaptura" class="meio-captura form-control" name="meiocaptura">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-2">
                  <button id="bt-selecionar-meios-captura" type="button" class="btn btn-sm bt-filtro-selecao" data-toggle="modal" data-target="#staticBackdropMeioCaptura">
                    <b>Selecionar</b>
                  </button>
                </div>

                <div class="col-sm-8">
                  <div id="grupo-filtros">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-4">
                          <h6> Código Autorização: </h6>
                          <input id="cod_autorizacao" class="form-control" name="modalidade">
                        </div>
                        <div class="col-sm-4">
                          <h6> Identificador Pagamento: </h6>
                          <input id="identificador_pagamento" class="form-control" name="modalidade">
                        </div>
                        <div class="col-sm-4">
                          <h6> NSU: </h6>
                          <input id="nsu" class="form-control" name="modalidade">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <h6>Status Conciliação:</h6>
                          <div class="row status-conciliacao">
                            @foreach($status_conciliacao as $status)
                              <div class="check-group">
                                <input id="{{ "statusFinan".$status->CODIGO }}" type="checkbox" value="{{ $status->CODIGO }}" name="status_conciliacao[]" class="status-conciliacao-checkbox" data-codigo="{{ $status->CODIGO }}" checked required>
                                <label for="{{ "statusFinan".$status->CODIGO }}">{{ $status->STATUS_CONCILIACAO}}</label>
                              </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <div id="btfiltro">
                    <a form="myform" type="reset" class="btn btn-sm bt-limpar-form"> <i class="far fa-trash-alt"></i> <b>Limpar Campos</b>  </a>

                    <a id="bt-pesquisar" class="btn btn-sm"> <i class="fas fa-search"></i> <b>Pesquisar</b>  </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade modal-filtro modal-adquirentes" id="staticBackdropAdquirente" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">Adquirente</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <h6> Pesquisar </h6>
                    </div>
                    <div class="col-sm-12">
                      <input id="ft" class="form-control" onKeyDown="filtroNomeAdquirente({{$adquirentes}})">
                    </div>
                  </div>
                  <br>

                  <div class="row">
                    <div class="col-sm-10">
                      <p><b>Adquirente</b></p>
                    </div>
                    <div class="col-sm-2">
                      <input class="selecionar-tudo" data-seletor="adquirente" type="checkbox">
                    </div>
                    @if(isset($adquirentes))
                      @foreach($adquirentes as $adquirente)
                        <div id="{{ $adquirente->ADQUIRENTE }}" class="col-sm-10 opcao-check">
                          <p>{{ $adquirente->ADQUIRENTE }}</p>
                        </div>

                        <div id="{{ "divCod".$bandeira->CODIGO }}" class="col-sm-2 opcao-check">
                          <input id="{{ $adquirente->CODIGO }}" value="{{ $adquirente->ADQUIRENTE }}" class="adquirente" data-codigo="{{ $adquirente->CODIGO }}" data-descricao="{{ $adquirente->ADQUIRENTE }}" name="arrayAdquirentes[]" type="checkbox">
                        </div>
                        <hr>
                      @endforeach
                    @endif
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
                  <button type="button" class="btn btn-success bt-confirmar-selecao" data-dismiss="modal"><b>Confirmar</b></button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade modal-filtro modal-meio-captura" id="staticBackdropMeioCaptura" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">Meio de Captura</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <h6> Pesquisar </h6>
                    </div>
                    <div class="col-sm-12">
                      <input id="ftMeioCaptura" class="form-control" onKeyDown="filtroMeioCaptura({{$meio_captura}})">
                    </div>
                  </div>
                  <br>

                  <div class="row">
                    <div class="col-sm-10">
                      <p><b>MEIO DE CAPTURA</b></p>
                    </div>
                    <div class="col-sm-2">
                      <input class="selecionar-tudo" data-seletor="meio-captura" type="checkbox">
                    </div>
                    @if(isset($meio_captura))
                      @foreach($meio_captura as $meio)
                        <div class="col-sm-10 opcao-check">
                          <p>{{ $meio->DESCRICAO }}</p>
                        </div>
                        <div class="col-sm-2 opcao-check">
                          <input class="meio-captura" data-codigo="{{ $meio->CODIGO }}" data-descricao="{{ $meio->DESCRICAO }}" value="{{ $meio->CODIGO }}" name="arrayMeioCaptura[]" type="checkbox">
                        </div>
                        <hr>
                      @endforeach
                    @endif
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
                  <button type="button" class="btn btn-success bt-confirmar-selecao" data-dismiss="modal"><b>Confirmar</b></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>


    <div id="resultadosPesquisa" class="hidden">
      <div class="row" id="foo">
        <div  class="col-sm-2"></div>
        <div class="col-sm-10">
          <div class="dropdown">
            <a class="btn btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-file-download"></i>
              <b>Exportar</b>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
              <a class="dropdown-item" id="dp-item"  href="{{ action('VendasController@downloadTable') }}">  PDF</a>
              <a class="dropdown-item" id="dp-item"  onclick="download_table_as_csv('mytable');" href="#">  CSV</a>
            </div>
          </div>
        </div>
      </div>
      <br>

      <div class="table-wrapper">
        <table id="jsgrid-table" class="table">
          <thead>
            <tr>
              <th>
                <div class="d-flex flex-column justify-content-end">
                  <p class="mb-0">Detalhes</p>
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Data Venda</p>
                  <input type="text">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Previs. PGT</p>
                  <input type="text">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>NSU</p>
                  <input type="text">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Total Venda</p>
                  <input type="text">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Nº Parcela</p>
                  <input type="text">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Total Parcela</p>
                  <input type="text">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Liq. Parcela</p>
                  <input type="text">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Descrição ERP</p>
                  <input type="text">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Cod. Autorização</p>
                  <input type="text">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>ID. Venda Cliente</p>
                  <input type="text">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Meio de Captura</p>
                  <input type="text">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Status Conciliação</p>
                  <input type="text">
                </div>
               </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Justificativa</p>
                  <input type="text">
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

      <footer class="d-flex justify-content-between align-items-end flex-wrap">
        <nav class="nav-paginacao">
          <ul class="pagination">
          </ul>
        </nav>

        <div class="form-group">
          <label for="quantidadePorPagina">Quantidade por página</label>
          <select name="porPagina" id="quantidadePorPagina" class="form-control">
            <option value="10" selected>10</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="200">200</option>
          </select>
        </div>
      </footer>
    </div>
  </div>
@stop

@section('footerScript')
  <script src="{{ URL::asset('assets/js/vendas/vendaserp.js') }}"></script>

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

