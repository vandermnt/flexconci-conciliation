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
                  <div class="d-flex align-items-center justify-content-space-between flex-wrap filtros-datas">
                    <div class="form-group flex-grow-1">
                      <label id="data_inicial">Data Inicial:</label>
                      <input
                        id="data_inicial"
                        class="form-control"
                        type="date"
                        name="data_inicial"
                        value="{{ date("Y-m-01") }}"
                      >
                    </div>
                    <div class="form-group flex-grow-1">
                      <label id="data_final">Data Final:</label>
                      <input
                        id="data_final"
                        class="form-control"
                        type="date"
                        name="data_final"
                        value="{{ date("Y-m-d") }}"
                      >
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="empresa">Empresa:</label>
                <div class="input-group row mr-0">
                  <div class="col-sm-6 d-flex align-items-center pr-0">
                    <input
                      id="empresa"
                      class="form-control"
                      type="text"
                      name="empresa"
                      data-group="empresa"
                      data-checker="to-text-element"
                    >
                  </div>
                  <div class="col-12 col-sm-4 col-md-2  d-flex align-items-center pr-0">
                    <button
                      type="button"
                      class="btn btn-sm bt-filtro-selecao"
                      data-toggle="modal"
                      data-target="#empresasModal"
                    >
                      Selecionar
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label for="adquirente">Adquirente:</label>
                <div class="input-group row mr-0">
                  <div class="col-sm-6 d-flex align-items-center pr-0">
                    <input
                      id="adquirente"
                      class="form-control"
                      type="text"
                      name="adquirente"
                      data-group="adquirente"
                      data-checker="to-text-element"
                    >
                  </div>
                  <div class="col-12 col-sm-4 col-md-2  d-flex align-items-center pr-0">
                    <button
                      type="button"
                      class="btn btn-sm bt-filtro-selecao"
                      data-toggle="modal"
                      data-target="#adquirentesModal"
                    >
                      Selecionar
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label for="bandeira">Bandeira:</label>
                <div class="input-group row mr-0">
                  <div class="col-sm-6 d-flex align-items-center pr-0">
                    <input
                      id="bandeira"
                      class="form-control"
                      type="text"
                      data-group="bandeira"
                      name="bandeira"
                      data-checker="to-text-element"
                    >
                  </div>
                  <div class="col-12 col-sm-4 col-md-2  d-flex align-items-center pr-0">
                    <button
                      type="button"
                      class="btn btn-sm bt-filtro-selecao"
                      data-toggle="modal"
                      data-target="#bandeirasModal"
                    >
                      Selecionar
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label for="modalidade">Forma de Pagamento:</label>
                <div class="input-group row mr-0">
                  <div class="col-sm-6 d-flex align-items-center pr-0">
                    <input
                      id="modalidade"
                      class="form-control"
                      type="text"
                      data-group="modalidade"
                      name="modalidade"
                      data-checker="to-text-element"
                    >
                  </div>
                  <div class="col-12 col-sm-4 col-md-2  d-flex align-items-center pr-0">
                    <button
                      type="button"
                      class="btn btn-sm bt-filtro-selecao"
                      data-toggle="modal"
                      data-target="#modalidadesModal"
                    >
                      Selecionar
                    </button>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6 filtro-id-erp">
                  <div class="form-group">
                    <label for="id_erp">ID. ERP:</label>
                    <input
                      id="id_erp"
                      class="form-control"
                      type="text"
                    >
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label for="status_conciliacao">Status Conciliação:</label>
                    <div class="d-flex align-items-center flex-wrap">
                      @foreach($status_conciliacao as $status)
                        <div class="check-group">
                          <input
                            id="status-conciliacao-{{ $status->CODIGO }}"
                            class="status-conciliacao-checkbox"
                            type="checkbox"
                            name="status_conciliacao[]"
                            value="{{ $status->CODIGO }}"
                            data-group="status-conciliacao"
                            data-checker="checkbox"
                            data-codigo="{{ $status->CODIGO }}"
                            checked
                          >
                          <label
                            for="status-conciliacao-{{ $status->CODIGO }}"
                          >
                            {{ $status->STATUS_CONCILIACAO }}
                          </label>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label for="status_conciliacao">Status Financeiro:</label>
                    <div class="d-flex align-items-center flex-wrap">
                      @foreach($status_financeiro as $status)
                        <div class="check-group">
                          <input
                            id="status-financeiro-{{ $status->CODIGO }}"
                            class="status-financeiro-checkbox"
                            type="checkbox"
                            name="status_financeiro[]"
                            value="{{ $status->CODIGO }}"
                            data-group="status-financeiro"
                            data-checker="checkbox"
                            data-codigo="{{ $status->CODIGO }}"
                            checked
                          >
                          <label
                            for="status-financeiro-{{ $status->CODIGO }}"
                          >
                            {{ $status->STATUS_FINANCEIRO }}
                          </label>
                        </div>
                      @endforeach
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
        </div>
      </div>
      
      <div
        id="empresasModal"
        class="modal fade modal-filtro"
        data-backdrop="static"
        data-keyboard="false"
        role="dialog"
        aria-labelledby="empresasLabel"
        aria-hidden="true"
        tabindex="-1"
      >
        <div class="modal-dialog modal-dialog-lg">
          <div class="modal-content">
            <header class="modal-header d-flex align-items-center">
              <h5 class="modal-title" id="empresasLabel">Empresa</h5>
              <button
                class="close"
                type="button"
                data-acao="cancelar"
                data-group="empresa"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </header>
            <main class="modal-body">
              <div class="form-group">
                <h6>Pesquisar</h6>
                <input
                  type="text"
                  data-filter-group="empresa"
                  data-filter-fields="cnpj,empresa"
                  class="form-control"
                >
              </div>
              <div class="modal-checkboxes">
                <div class="row">
                  <div class="col-sm-6 pl-0">
                    <p>Empresa</p>
                  </div>
                  <div class="col-sm-4">
                    <p>CNPJ</p>
                  </div>
                  <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                    <input
                      type="checkbox"
                      data-checker="global"
                      data-group="empresa"
                    >
                  </div>
                </div>
                @isset($empresas)
                  @foreach($empresas as $empresa)
                    <div class="row"
                      data-filter-item-container="empresa"
                      data-filter-empresa="{{ $empresa->NOME_EMPRESA }}"
                      data-filter-cnpj="{{ $empresa->CNPJ }}" 
                    >
                      <div class="col-sm-6 pl-0">
                        <p>{{ $empresa->NOME_EMPRESA }}</p>
                      </div>
                      <div class="col-sm-4">
                        <p>{{ $empresa->CNPJ }}</p>
                      </div>
                      <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                        <input
                          type="checkbox"
                          name="empresas[]"
                          value="{{ $empresa->CODIGO }}"
                          data-checker="checkbox"
                          data-group="empresa"
                          data-descricao="{{ $empresa->NOME_EMPRESA }}"
                        >
                      </div>
                    </div>
                  @endforeach
                @endisset
              </div>
            </main>
            <footer class="modal-footer">
              <button
                type="button"
                class="btn btn-danger"
                data-acao="cancelar"
                data-group="empresa"
                data-dismiss="modal"
              >
                Cancelar
              </button>
              <button
                type="button"
                class="btn btn-success"
                data-acao="confirmar"
                data-group="empresa"
                data-dismiss="modal"
              >
                Confirmar
              </button>
            </footer>
          </div>
        </div>
      </div>

      <div
        id="adquirentesModal"
        class="modal fade modal-filtro"
        data-backdrop="static"
        data-keyboard="false"
        role="dialog"
        aria-labelledby="adquirentesLabel"
        aria-hidden="true"
        tabindex="-1"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <header class="modal-header d-flex align-items-center">
              <h5 class="modal-title" id="adquirentesLabel">Adquirente</h5>
              <button
                class="close"
                type="button"
                data-acao="cancelar"
                data-group="adquirente"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </header>
            <main class="modal-body">
              <div class="form-group">
                <h6>Pesquisar</h6>
                <input
                  type="text"
                  class="form-control"
                  data-filter-group="adquirente"
                  data-filter-fields="adquirente"
                >
              </div>
              <div class="modal-checkboxes">
                <div class="row">
                  <div class="col-sm-10 pl-0">
                    <p>Adquirente</p>
                  </div>
                  <div class="col-sm-2 pl-0 d-flex align-items-start px-0 justify-content-end">
                    <input
                      type="checkbox"
                      data-checker="global"
                      data-group="adquirente"
                    >
                  </div>
                </div>
                @isset($adquirentes)
                  @foreach($adquirentes as $adquirente)
                    <div
                      class="row"
                      data-filter-item-container="adquirente"
                      data-filter-adquirente="{{ $adquirente->ADQUIRENTE }}"
                    >
                      <div class="col-sm-10 pl-0">
                        <p>{{ $adquirente->ADQUIRENTE }}</p>
                      </div>
                      <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                        <input
                          type="checkbox"
                          name="arrayAdquirentes[]"
                          value="{{ $adquirente->CODIGO }}"
                          data-checker="checkbox"
                          data-group="adquirente"
                          data-descricao="{{ $adquirente->ADQUIRENTE }}"
                        >
                      </div>
                    </div>
                  @endforeach
                @endisset
              </div>
            </main>
            <footer class="modal-footer">
              <button
                type="button"
                class="btn btn-danger"
                data-acao="cancelar"
                data-group="adquirente"
                data-dismiss="modal"
              >
                Cancelar
              </button>
              <button
                type="button"
                class="btn btn-success"
                data-acao="confirmar"
                data-group="adquirente"
                data-dismiss="modal"
              >
                Confirmar
              </button>
            </footer>
          </div>
        </div>
      </div>
      
      <div
        id="bandeirasModal"
        class="modal fade modal-filtro"
        data-backdrop="static"
        data-keyboard="false"
        role="dialog"
        aria-labelledby="bandeirasLabel"
        aria-hidden="true"
        tabindex="-1"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <header class="modal-header d-flex align-items-center">
              <h5 class="modal-title" id="bandeirasLabel">Bandeira</h5>
              <button
                class="close"
                type="button"
                data-acao="cancelar"
                data-group="bandeira"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </header>
            <main class="modal-body">
              <div class="form-group">
                <h6>Pesquisar</h6>
                <input
                  type="text"
                  class="form-control"
                  data-filter-group="bandeira"
                  data-filter-fields="bandeira"
                >
              </div>
              <div class="modal-checkboxes">
                <div class="row">
                  <div class="col-sm-10 pl-0">
                    <p>Bandeira</p>
                  </div>
                  <div class="col-sm-2 pl-0 d-flex align-items-start px-0 justify-content-end">
                    <input
                      type="checkbox"
                      data-checker="global"
                      data-group="bandeira"
                    >
                  </div>
                </div>
                @isset($bandeiras)
                  @foreach($bandeiras as $bandeira)
                    <div
                      class="row"
                      data-filter-item-container="bandeira"
                      data-filter-bandeira="{{ $bandeira->BANDEIRA }}"
                    >
                      <div class="col-sm-10 pl-0">
                        <p>{{ $bandeira->BANDEIRA }}</p>
                      </div>
                      <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                        <input
                          type="checkbox"
                          name="bandeiras[]"
                          value="{{ $bandeira->CODIGO }}"
                          data-checker="checkbox"
                          data-group="bandeira"
                          data-descricao="{{ $bandeira->BANDEIRA }}"
                        >
                      </div>
                    </div>
                  @endforeach
                @endisset
              </div>
            </main>
            <footer class="modal-footer">
              <button
                type="button"
                class="btn btn-danger"
                data-acao="cancelar"
                data-group="bandeira"
                data-dismiss="modal"
              >
                Cancelar
              </button>
              <button
                type="button"
                class="btn btn-success"
                data-acao="confirmar"
                data-group="bandeira"
                data-dismiss="modal"
              >
                Confirmar
              </button>
            </footer>
          </div>
        </div>
      </div>
      
      <div
        id="modalidadesModal"
        class="modal fade modal-filtro"
        data-backdrop="static"
        data-keyboard="false"
        role="dialog"
        aria-labelledby="modalidadesLabel"
        aria-hidden="true"
        tabindex="-1"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <header class="modal-header d-flex align-items-center">
              <h5 class="modal-title" id="modalidadesLabel">Forma de Pagamento</h5>
              <button
                class="close"
                type="button"
                data-acao="cancelar"
                data-group="modalidade"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </header>
            <main class="modal-body">
              <div class="form-group">
                <h6>Pesquisar</h6>
                <input
                  type="text"
                  class="form-control"
                  data-filter-group="modalidade"
                  data-filter-fields="modalidade"
                >
              </div>
              <div class="modal-checkboxes">
                <div class="row">
                  <div class="col-sm-10 pl-0">
                    <p>Forma de Pagamento</p>
                  </div>
                  <div class="col-sm-2 pl-0 d-flex align-items-start px-0 justify-content-end">
                    <input
                      type="checkbox"
                      data-checker="global"
                      data-group="modalidade"
                    >
                  </div>
                </div>
                @isset($modalidades)
                  @foreach($modalidades as $modalidade)
                    <div
                      class="row"
                      data-filter-item-container="modalidade"
                      data-filter-modalidade="{{ $modalidade->DESCRICAO }}"
                    >
                      <div class="col-sm-10 pl-0">
                        <p>{{ $modalidade->DESCRICAO }}</p>
                      </div>
                      <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                        <input
                          type="checkbox"
                          name="modalidades[]"
                          value="{{ $modalidade->CODIGO }}"
                          data-checker="checkbox"
                          data-group="modalidade"
                          data-descricao="{{ $modalidade->DESCRICAO }}"
                        >
                      </div>
                    </div>
                  @endforeach
                @endisset
              </div>
            </main>
            <footer class="modal-footer">
              <button
                type="button"
                class="btn btn-danger"
                data-acao="cancelar"
                data-group="modalidade"
                data-dismiss="modal"
              >
                Cancelar
              </button>
              <button
                type="button"
                class="btn btn-success"
                data-acao="confirmar"
                data-group="modalidade"
                data-dismiss="modal"
              >
                Confirmar
              </button>
            </footer>
          </div>
        </div>
      </div>
    </form>


    <div id="resultadosPesquisa" class="hidden">
      <section class="row boxes">
        <div class="col-sm-12 col-md-6 col-lg-3 d-flex">
          <div class="card flex-grow-1">
            <div class="card-body">
              <p class="card-title text-dark font-weight-bold">QTD</p>
              <div class="d-flex align-items-center justify-content-between">
                <h4 id="js-qtd-box">0</h4>
                <img
                  src="assets/images/vendasoperadora/quantidade.png"
                  alt="Quantidade Vendas ERP"
                >
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3 d-flex">
          <div class="card flex-grow-1">
            <div class="card-body">
              <p class="card-title text-dark font-weight-bold">BRUTO</p>
              <div class="d-flex align-items-center justify-content-between">
                <h4 id="js-bruto-box">R$ 0,00</h4>
                <img
                src="assets/images/vendasoperadora/bruto.png"
                alt="Valor Bruto"
                >
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3 d-flex">
          <div class="card flex-grow-1">
            <div class="card-body">
              <p class="card-title text-dark font-weight-bold">TAXA</p>
              <div class="d-flex align-items-center justify-content-between">
                <h4 class="text-danger" id="js-taxa-box">R$ 0,00</h4>
                <img
                src="assets/images/vendasoperadora/percentagem.png"
                alt="Valor Taxa"
                >
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3 d-flex">
          <div class="card flex-grow-1">
            <div class="card-body">
              <p class="card-title text-dark font-weight-bold">VALOR LÍQUIDO DE VENDAS</p>
              <div class="d-flex align-items-center justify-content-between">
                <h4 id="js-liquido-box">R$ 0,00</h4>
                <img
                src="assets/images/vendasoperadora/liquido.png"
                alt="Valor Líquido"
                >
              </div>
            </div>
          </div>
        </div>
      </section>

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
                  <p>TID</p>
                  <input type="text" name="TID">
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
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Data Importação</p>
                  <input type="text" name="CAMPO3">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Hora Importação</p>
                  <input type="text" name="CAMPO3">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Data Conciliação</p>
                  <input type="text" name="CAMPO3">
                </div>
              </th>
              <th>
                <div class="d-flex flex-column align-items-center">
                  <p>Hora Conciliação</p>
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
          <ul class="pagination"></ul>
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
  <script defer src="{{ URL::asset('assets/js/lib/modal-filters.js') }}"></script>
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

