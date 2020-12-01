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

  <div id="tudo_page" class="container-fluid hidden">
    <div class="row">
      <div class="col-sm-12">
        @component('common-components.breadcrumb')
        @slot('title') Vendas ERP @endslot
        @slot('item1') Vendas @endslot
        <!-- @slot('item2') Antecipação de Venda @endslot -->
        @endcomponent
      </div>
    </div>
    <form id="form-pesquisa" action="{{ action('VendasErpController@buscarVendasErp') }}" method="post">
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
                  <div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">
                          <h6> Empresa: </h6>
                          <input
                            data-group="empresa"
                            data-checker="to-text-element"
                            id="empresa" class="empresa form-control" name="empresa">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-2">
                  <button type="button" class="btn btn-sm bt-filtro-selecao" data-toggle="modal" data-target="#empresaModal">
                    <b>Selecionar</b>
                  </button>
                </div>

                <div class="col-sm-6">
                  <div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">
                          <h6> Adquirente: </h6>
                          <input
                            data-group="adquirente"
                            data-checker="to-text-element" 
                            id="adquirente" class="adquirente form-control" name="adquirente">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-2">
                  <button type="button" class="btn btn-sm bt-filtro-selecao" data-toggle="modal" data-target="#staticBackdropAdquirente">
                    <b>Selecionar</b>
                  </button>
                </div>

                <div class="col-sm-6">
                  <div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">
                          <h6> Bandeira: </h6>
                          <input
                            data-group="bandeira"
                            data-checker="to-text-element" 
                            id="bandeira" class="form-control" name="bandeira">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-2">
                  <button type="button" class="btn btn-sm bt-filtro-selecao" data-toggle="modal" data-target="#bandeirasModal">
                    <b>Selecionar</b>
                  </button>
                </div>

                <div class="col-sm-6">
                  <div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">
                          <h6> Forma de Pagamento: </h6>
                          <input
                            data-group="modalidade"
                            data-checker="to-text-element" 
                            id="modalidade" class="form-control" name="modalidade">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-2">
                  <button type="button" class="btn btn-sm bt-filtro-selecao" data-toggle="modal" data-target="#modalidadesModal">
                    <b>Selecionar</b>
                  </button>
                </div>

                <div class="col-sm-8">
                  <div id="grupo-filtros">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-4">
                          <h6> ID. ERP: </h6>
                          <input id="id_erp" class="form-control" name="id_erp">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <h6>Status Conciliação:</h6>
                          <div class="row status-conciliacao">
                            @foreach($status_conciliacao as $status)
                              <div class="check-group">
                                <input 
                                  id="{{ "statusConc".$status->CODIGO }}"
                                  type="checkbox"
                                  value="{{ $status->CODIGO }}"
                                  name="status_conciliacao[]"
                                  class="status-conciliacao-checkbox"
                                  data-group="status-conciliacao"
                                  data-checker="checkbox"
                                  data-codigo="{{ $status->CODIGO }}"
                                  checked
                                  required
                                >
                                <label for="{{ "statusConc".$status->CODIGO }}">{{ $status->STATUS_CONCILIACAO}}</label>
                              </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <h6>Status Financeiro:</h6>
                          <div class="row status-financeiro">
                            @foreach($status_financeiro as $status)
                              <div class="check-group">
                                <input 
                                  id="{{ "statusFinan".$status->CODIGO }}"
                                  type="checkbox"
                                  value="{{ $status->CODIGO }}"
                                  name="status_financeiro[]"
                                  class="status-financeiro-checkbox"
                                  data-group="status-financeiro"
                                  data-checker="checkbox"
                                  data-codigo="{{ $status->CODIGO }}"
                                  checked
                                  required
                                >
                                <label for="{{ "statusFinan".$status->CODIGO }}">{{ $status->STATUS_FINANCEIRO }}</label>
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
                    <a form="form-pesquisa" type="reset" class="btn btn-sm bt-limpar-form"> <i class="far fa-trash-alt"></i> <b>Limpar Campos</b>  </a>

                    <a id="bt-pesquisar" class="btn btn-sm"> <i class="fas fa-search"></i> <b>Pesquisar</b>  </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade modal-filtro modal-empresas" id="empresaModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">Empresa</h5>
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
                      <input class="form-control">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-5">
                      <p><b>EMPRESA</b></p>
                    </div>
                    <div class="col-sm-5">
                      <p><b>CNPJ</b></p>
                    </div>
                    <div class="col-sm-2">
                      <input
                        type="checkbox"
                        class="selecionar-tudo"
                        data-checker="global"
                        data-group="empresa"
                      >
                    </div>
                    @if(isset($empresas))
                      @foreach($empresas as $empresa)
                        <div class="col-sm-5 opcao-check">
                          <p>{{ $empresa->NOME_EMPRESA }}</p>
                        </div>
                        <div class="col-sm-5 opcao-check">
                          <p>{{ $empresa->CNPJ }}</p>
                        </div>

                        <div class="col-sm-2 opcao-check">
                          <input 
                            id="{{ "empresa-".$empresa->CODIGO }}"
                            type="checkbox"
                            class="empresa"
                            name="empresas[]"
                            value="{{ $empresa->CODIGO }}"
                            data-checker="checkbox"
                            data-group="empresa"
                            data-codigo="{{ $empresa->CODIGO }}"
                            data-descricao="{{ $empresa->NOME_EMPRESA }}"
                          >
                        </div>
                        <hr>
                      @endforeach
                    @endif
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" data-acao="cancelar" class="btn btn-danger" data-group="empresa" data-dismiss="modal">
                    Cancelar
                  </button>
                  <button type="button" data-acao="confirmar" class="btn btn-success" data-group="empresa" data-dismiss="modal">
                    Confirmar
                  </button>
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
                      <input class="form-control">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-10">
                      <p><b>Adquirente</b></p>
                    </div>
                    <div class="col-sm-2">
                      <input
                        type="checkbox"
                        class="selecionar-tudo"
                        data-checker="global"
                        data-group="adquirente"
                      >
                    </div>
                    @if(isset($adquirentes))
                      @foreach($adquirentes as $adquirente)
                        <div class="col-sm-10 opcao-check">
                          <p>{{ $adquirente->ADQUIRENTE }}</p>
                        </div>

                        <div class="col-sm-2 opcao-check">
                          <input 
                            id="{{ "adquirente-".$adquirente->CODIGO }}"
                            type="checkbox"
                            class="adquirente"
                            name="arrayAdquirentes[]"
                            value="{{ $adquirente->CODIGO }}"
                            data-checker="checkbox"
                            data-group="adquirente"
                            data-codigo="{{ $adquirente->CODIGO }}"
                            data-descricao="{{ $adquirente->ADQUIRENTE }}"
                          >
                        </div>
                        <hr>
                      @endforeach
                    @endif
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" data-acao="cancelar" class="btn btn-danger" data-group="adquirente" data-dismiss="modal">
                    Cancelar
                  </button>
                  <button type="button" data-acao="confirmar" class="btn btn-success" data-group="adquirente" data-dismiss="modal">
                    Confirmar
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade modal-filtro modal-bandeiras" id="bandeirasModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">Bandeira</h5>
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
                      <input class="form-control">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-10">
                      <p><b>BANDEIRA</b></p>
                    </div>
                    <div class="col-sm-2">
                      <input 
                        type="checkbox"
                        data-checker="global"
                        data-group="bandeira"
                        data-seletor="bandeira"
                      >
                    </div>
                    @if(isset($bandeiras))
                      @foreach($bandeiras as $bandeira)
                        <div class="col-sm-10 opcao-check">
                          <p>{{ $bandeira->BANDEIRA }}</p>
                        </div>
                        <div class="col-sm-2 opcao-check">
                          <input
                            type="checkbox"
                            name="bandeiras[]"
                            value="{{ $bandeira->CODIGO }}"
                            class="bandeira"
                            data-checker="checkbox"
                            data-group="bandeira"
                            data-codigo="{{ $bandeira->CODIGO }}"
                            data-descricao="{{ $bandeira->BANDEIRA }}"
                          >
                        </div>
                        <hr>
                      @endforeach
                    @endif
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" data-acao="cancelar" class="btn btn-danger" data-group="bandeira" data-dismiss="modal">
                    Cancelar
                  </button>
                  <button type="button" data-acao="confirmar" data-group="bandeira" class="btn btn-success bt-confirmar-selecao" data-dismiss="modal">
                    Confirmar
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade modal-filtro modal-modalidades" id="modalidadesModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">Forma de Pagamento</h5>
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
                      <input class="form-control">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-10">
                      <p><b>FORMA DE PAGAMENTO</b></p>
                    </div>
                    <div class="col-sm-2">
                      <input 
                        type="checkbox"
                        data-checker="global"
                        data-group="modalidade"
                        data-seletor="modalidade"
                      >
                    </div>
                    @if(isset($modalidades))
                      @foreach($modalidades as $modalidade)
                        <div class="col-sm-10 opcao-check">
                          <p>{{ $modalidade->DESCRICAO }}</p>
                        </div>
                        <div class="col-sm-2 opcao-check">
                          <input
                            type="checkbox"
                            name="modalidades[]"
                            value="{{ $modalidade->CODIGO }}"
                            class="modalidade"
                            data-checker="checkbox"
                            data-group="modalidade"
                            data-codigo="{{ $modalidade->CODIGO }}"
                            data-descricao="{{ $modalidade->DESCRICAO }}"
                          >
                        </div>
                        <hr>
                      @endforeach
                    @endif
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" data-acao="cancelar" class="btn btn-danger" data-group="modalidade" data-dismiss="modal">
                    Cancelar
                  </button>
                  <button type="button" data-acao="confirmar" data-group="modalidade" class="btn btn-success bt-confirmar-selecao" data-dismiss="modal">
                    Confirmar
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>


    <div id="resultadosPesquisa" class="hidden">
      <div class="acoes">
        <button id="js-exportar" class="btn font-weight-bold">
          <i class="fas fa-file-download"></i>
          Exportar
        </button>
      </div>

      <div class="table-wrapper">
        <table id="jsgrid-table" class="table">
          <thead>
            <tr>
              <th>
                <div class="d-flex flex-column justify-content-end">
                  <p class="mb-0">Ações</p>
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>ID. ERP</p>
                  <input type="text" name="ID_ERP">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Empresa</p>
                  <input type="text" name="NOME_EMPRESA">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>CNPJ</p>
                  <input type="text" name="CNPJ">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Venda</p>
                  <input type="text" name="DATA_VENDA">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Previsão</p>
                  <input type="text" name="DATA_VENCIMENTO">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Operadora</p>
                  <input type="text" name="ADQUIRENTE">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Bandeira</p>
                  <input type="text" name="BANDEIRA">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Forma de Pagamento</p>
                  <input type="text" name="MODALIDADE">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>NSU</p>
                  <input type="text" name="NSU">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Autorização</p>
                  <input type="text" name="CODIGO_AUTORIZACAO">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Cartão</p>
                  <input type="text" name="">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Valor Bruto</p>
                  <input type="text" name="TOTAL_VENDA">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Taxa %</p>
                  <input type="text" name="TAXA">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Taxa R$</p>
                  <input type="text" name="VALOR_TAXA">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Valor Líquido</p>
                  <input type="text" name="VALOR_LIQUIDO_PARCELA">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Parcela</p>
                  <input type="text" name="PARCELA">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Total Parc.</p>
                  <input type="text" name="TOTAL_PARCELAS">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Hora</p>
                  <input type="text" name="">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Estabelecimento</p>
                  <input type="text" name="">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Banco</p>
                  <input type="text" name="BANCO">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Agência</p>
                  <input type="text" name="AGENCIA">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Conta</p>
                  <input type="text" name="CONTA_CORRENTE">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Produto</p>
                  <input type="text" name="PRODUTO">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Meio de Captura</p>
                  <input type="text" name="MEIOCAPTURA">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Status Conciliação</p>
                  <input type="text" name="STATUS_CONCILIACAO">
                </div>
               </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Status Financeiro</p>
                  <input type="text" name="STATUS_FINANCEIRO">
                </div>
               </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Justificativa</p>
                  <input type="text" name="JUSTIFICATIVA">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Campo 1</p>
                  <input type="text" name="CAMPO1">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Campo 2</p>
                  <input type="text" name="CAMPO2">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Campo 3</p>
                  <input type="text" name="CAMPO3">
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
            <tr>
              <td class="bolder">Totais</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="bolder" data-chave="TOTAL_VENDAS"></td>
              <td></td>
              <td class="bolder text-danger" data-chave="TOTAL_TAXA"></td>
              <td class="bolder" data-chave="LIQUIDEZ_TOTAL_PARCELA"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tfoot>
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

    <div class="alert alert-success font-weight-bold alerta-quantidade-resultados hidden">
      <span>0</span> resultados encontrados.
    </div>
  </div>
@stop

@section('footerScript')
  <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
  <script src="https://unpkg.com/xlsx/dist/shim.min.js"></script>
  <script defer src="{{ URL::asset('assets/js/lib/api.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/pagination.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/checker.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/vendas/vendaserp.js') }}"></script>

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

