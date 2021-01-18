@extends('layouts.analytics-master')

@section('headerStyle')
  <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{{ URL::asset('assets/css/vendas/pagina-vendas-operadoras.css') }}" type="text/css">
@endsection

@section('content')
  <main id="pagina-vendas-operadoras" class="container-fluid">
    <header>
      @component('common-components.breadcrumb')
        @slot('title') Vendas Operadoras @endslot
        @slot('item1') Vendas @endslot
      @endcomponent
    </header>

    <div class="card">
      <div class="card-body">
        <form
          id="js-form-pesquisar"
          class="form search-form"
          method="POST"
          data-url-operadoras="{{ route('vendas-operadoras.index') }}"
          data-url-filtrar-operadoras="{{ route('vendas-operadoras.index') }}"
        >
          <div class="input-group">
            <div class="form-group flex-grow-1">
              <label for="data-inicial">Data Inicial:</label>
              <input
                id="data-inicial"
                class="form-control"
                type="date"
                name="data_inicial"
                value="{{ date("Y-m-01") }}"
                required
              >
            </div>
            <div class="form-group flex-grow-1">
              <label for="data-final">Data Final:</label>
              <input
                id="data-final"
                class="form-control"
                type="date"
                name="data_final"
                value="{{ date("Y-m-d") }}"
                required
              >
            </div>
          </div>
          
          <div class="input-group">
            <div class="form-group flex-grow-1">
              <label for="empresa">Empresa:</label>
              <input 
                id="empresa" 
                class="form-control"
                type="text"
              >
            </div>
            <button
              class="btn btn-sm form-button"
              type="button"
              data-toggle="modal"
              data-target="#empresas-modal"
            >
              Selecionar
            </button>
          </div>
          
          <div class="input-group">
            <div class="form-group flex-grow-1">
              <label for="adquirente">Adquirente:</label>
              <input 
                id="adquirente" 
                class="form-control"
                type="text"
              >
            </div>
            <button
              class="btn btn-sm form-button"
              type="button"
              data-toggle="modal"
              data-target="#adquirentes-modal"
            >
              Selecionar
            </button>
          </div>
          
          <div class="input-group">
            <div class="form-group flex-grow-1">
              <label for="bandeira">Bandeira:</label>
              <input 
                id="bandeira" 
                class="form-control"
                type="text"
              >
            </div>
            <button
              class="btn btn-sm form-button"
              type="button"
              data-toggle="modal"
              data-target="#bandeiras-modal"
            >
              Selecionar
            </button>
          </div>
          
          <div class="input-group">
            <div class="form-group flex-grow-1">
              <label for="modalidade">Forma de Pagamento:</label>
              <input 
                id="modalidade" 
                class="form-control"
                type="text"
              >
            </div>
            <button
              class="btn btn-sm form-button"
              type="button"
              data-toggle="modal"
              data-target="#modalidades-modal"
            >
              Selecionar
            </button>
          </div>
          
          <div class="input-group">
            <div class="form-group flex-grow-1">
              <label for="estabelecimento">Código de Estabelecimento:</label>
              <input 
                id="estabelecimento" 
                class="form-control"
                type="text"
              >
            </div>
            <button
              class="btn btn-sm form-button"
              type="button"
              data-toggle="modal"
              data-target="#estabelecimentos-modal"
            >
              Selecionar
            </button>
          </div>

          <div class="input-check-group">
            <label>Status Conciliação:</label>
            <div class="check-group">
              
              @isset($status_conciliacao)
                @foreach($status_conciliacao as $status)
                  <div class="form-group mr-2">
                    <input
                      id="status-conciliacao-{{ $status->CODIGO }}"
                      name="status_conciliacao"
                      value="{{ $status->CODIGO }}"
                      type="checkbox"
                      data-group="status-conciliacao"
                      data-checker="checkbox"
                      checked
                    >
                    <label
                      for="status-conciliacao-{{ $status->CODIGO }}"
                    >
                      {{ $status->STATUS_CONCILIACAO }}
                    </label>
                  </div>
                @endforeach
              @endisset
            </div>
          </div>
          
          <div class="input-check-group">
            <label>Status Financeiro:</label>
            <div class="check-group">
              @isset($status_financeiro)
                @foreach($status_financeiro as $status)
                  <div class="form-group mr-2">
                    <input
                      id="status-financeiro-{{ $status->CODIGO }}"
                      name="status_financeiro"
                      type="checkbox"
                      value="{{ $status->CODIGO }}"
                      data-group="status-financeiro"
                      data-checker="checkbox"
                      checked
                    >
                    <label for="status-financeiro-{{ $status->CODIGO }}">{{ $status->STATUS_FINANCEIRO }}</label>
                  </div>
                @endforeach
              @endisset
            </div>
          </div>

          <div class="button-group">
            <button
              class="btn btn-sm"
              type="button"
            >
              <i class="far fa-trash-alt"></i>
              Limpar Campos
            </button>
            <button
              class="btn btn-sm ml-1"
              type="button"
            >
              <i class="fas fa-search"></i>
              Pesquisar
            </button>
          </div>

          <div class="modais">
            <div
              id="empresas-modal"
              class="modal fade"
              role="dialog"
              tabindex="-1"
              data-backdrop="static"
              data-keyboard="false"
              aria-labelledby="empresas-label"
              aria-hidden="true"
            >
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="empresas-label">Empresa</h5>
                    <button
                      class="close"
                      type="button"
                      data-dismiss="modal"
                      data-acao="cancelar"
                      data-group="empresa"
                      data-label="Close"
                    >
                      <span aria-hidden="true">&times;</span>  
                    </button>
                  </header>
                  <main class="modal-body">
                    <div class="form-group">
                      <h6>Pesquisar</h6>
                      <input
                        class="form-control"
                        type="text"
                        data-filter-group="empresa"
                        data-filter-fields="cnpj,empresa"
                      >
                    </div>

                    <div class="modal-checkboxes">
                      <div class="row">
                        <div class="col-sm-6 pl-0">
                          <p>Empresa</p>
                        </div>
                        <div class="col-sm-4 px-0">
                          <p>CNPJ</p>
                        </div>
                        <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                          <input
                            type="checkbox"
                            data-group="empresa"
                            data-checker="global"
                          >
                        </div>
                      </div>
                      @isset($empresas)
                        @foreach($empresas as $empresa)
                          <div
                            class="row"
                            data-filter-item-container="empresa"
                            data-filter-empresa="{{ $empresa->NOME_EMPRESA }}"
                            data-filter-cnpj="{{ $empresa->CNPJ }}"
                          >
                            <div class="col-sm-6 pl-0">
                              <p>{{ $empresa->NOME_EMPRESA }}</p>
                            </div>
                            <div class="col-sm-4 px-0">
                              <p>{{ $empresa->CNPJ }}</p>
                            </div>
                            <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                              <input
                                type="checkbox"
                                name="grupos_clientes[]"
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
                      class="btn btn-danger font-weight-bold"
                      data-acao="cancelar"
                      data-group="empresa"
                      data-dismiss="modal"
                    >
                      Cancelar
                    </button>
    
                    <button
                      type="button"
                      class="btn btn-success font-weight-bold"
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
              id="adquirentes-modal"
              class="modal fade"
              data-backdrop="static"
              data-keyboard="false"
              role="dialog"
              aria-labelledby="adquirentes-label"
              aria-hidden="true"
              tabindex="-1"
            >
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="adquirentes-label">Adquirente</h5>
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
                                name="adquirentes[]"
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
              id="bandeiras-modal"
              class="modal fade"
              data-backdrop="static"
              data-keyboard="false"
              role="dialog"
              aria-labelledby="bandeiras-label"
              aria-hidden="true"
              tabindex="-1"
            >
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="bandeiras-label">Bandeira</h5>
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
              id="modalidades-modal"
              class="modal fade"
              data-backdrop="static"
              data-keyboard="false"
              role="dialog"
              aria-labelledby="modalidades-label"
              aria-hidden="true"
              tabindex="-1"
            >
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="modalidades-label">Forma de Pagamento</h5>
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
                            data-group="modalidadde"
                          >
                        </div>
                      </div>
                      @isset($modalidades)
                        @foreach($modalidades as $modalidade)
                          <div
                            class="row"
                            data-filter-item-container="modalidade"
                            data-filter-modalidaade="{{ $modalidade->DESCRICAO }}"
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
            
            <div
              id="estabelecimentos-modal"
              class="modal fade"
              data-backdrop="static"
              data-keyboard="false"
              role="dialog"
              aria-labelledby="estabelecimentos-label"
              aria-hidden="true"
              tabindex="-1"
            >
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="estabelecimentos-label">Código de Estabelecimento</h5>
                    <button
                      class="close"
                      type="button"
                      data-acao="cancelar"
                      data-group="estabelecimento"
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
                          <p>Código de Estabelec.</p>
                        </div>
                        <div class="col-sm-2 pl-0 d-flex align-items-start px-0 justify-content-end">
                          <input
                            type="checkbox"
                            data-checker="global"
                            data-group="estabelecimento"
                          >
                        </div>
                      </div>
                      @isset($estabelecimentos)
                        @foreach($estabelecimentos as $estabelecimento)
                          <div
                            class="row"
                            data-filter-item-container="modalidade"
                            data-filter-estabelecimento="{{ $estabelecimento->ESTABELECIMENTO }}"
                          >
                            <div class="col-sm-10 pl-0">
                              <p>{{ $estabelecimento->ESTABELECIMENTO }}</p>
                            </div>
                            <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                              <input
                                type="checkbox"
                                name="estabelecimentos[]"
                                value="{{ $estabelecimento->ESTABELECIMENTO }}"
                                data-checker="checkbox"
                                data-group="estabelecimento"
                                data-descricao="{{ $estabelecimento->ESTABELECIMENTO }}"
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
                      data-group="estabelecimento"
                      data-dismiss="modal"
                    >
                      Cancelar
                    </button>
                    <button
                      type="button"
                      class="btn btn-success"
                      data-acao="confirmar"
                      data-group="estabelecimento"
                      data-dismiss="modal"
                    >
                      Confirmar
                    </button>
                  </footer>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="resultados">
      <div class="boxes">
        <div class="card box">
          <div class="card-body">
            <h4>BRUTO</h4>
            <div class="d-flex align-items-center justify-content-between">
              <p id="js-bruto-box">R$ 337.204,53</p>
              <img src="{{ url('assets/images/vendasoperadora/bruto.png')}}" alt="Valor Bruto">
          </div>
          </div>
        </div>
        <div class="card box">
          <div class="card-body">
            <h4>VALOR TAXA</h4>
            <div class="d-flex align-items-center justify-content-between">
              <p id="js-taxa-box" class="text-danger">R$ -4.391,49</p>
              <img src="{{ url('assets/images/vendasoperadora/percentagem.png')}}" alt="Valor Taxa">
            </div>
          </div>
        </div>
        <div class="card box">
          <div class="card-body">
            <h4>TARIFA MÍNIMA</h4>
            <div class="d-flex align-items-center justify-content-between">
              <p id="js-tarifa-box" class="text-danger">R$ 0,00</p>
              <img src="{{ url('assets/images/vendasoperadora/percentagem.png')}}" alt="Tarifa Mínima">
            </div>
          </div>
        </div>
        <div class="card box">
          <div class="card-body">
            <h4>VALOR LÍQUIDO DE VENDAS</h4>
            <div class="d-flex align-items-center justify-content-between">
              <p id="js-liquido-box">R$ 332.813,04</p>
              <img src="{{ url('assets/images/vendasoperadora/liquido.png')}}" alt="Valor Líquido">
            </div>
          </div>
        </div>
      </div>

      <div class="vendas">
        <div class="tabela-info d-flex align-items-center justify-content-between">
          <h4>Vendas Operadoras <span id="js-vendas-erp-info"></span></h4>
          <div class="acoes d-flex align-items-center justify-content-end">
            <button id="js-exportar-erp" class="btn button no-hover">
              <i class="fas fa-file-download"></i>
              Exportar
            </button>
          </div>
        </div>

        <div class="table-responsive mt-3">
          <table class="table table-striped" id="js-tabela-vendas">
            <thead>
              <tr>
                <th>
                  <div class="d-flex flex-column justify-content-end">
                    <p class="m-0">Ações</p>
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>ID. ERP</p>
                    <input type="text" class="form-control" name="ID_ERP">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Empresa</p>
                    <input type="text" class="form-control" name="NOME_EMPRESA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>CNPJ</p>
                    <input type="text" class="form-control" name="CNPJ">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Venda</p>
                    <input type="date" class="form-control" name="DATA_VENDA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Previsão</p>
                    <input type="date" class="form-control" name="DATA_PREVISAO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Operadora</p>
                    <input type="text" class="form-control" name="ADQUIRENTE">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Bandeira</p>
                    <input type="text" class="form-control" name="BANDEIRA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Forma de Pagamento</p>
                    <input type="text" class="form-control" name="MODALIDADE">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>NSU</p>
                    <input type="text" class="form-control" name="NSU">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Autorização</p>
                    <input type="text" class="form-control" name="AUTORIZACAO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Valor Bruto</p>
                    <input type="number" min="0" step="0.01" class="form-control" name="VALOR_BRUTO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Taxa %</p>
                    <input type="number" min="0" step="0.01" class="form-control" name="PERCENTUAL_TAXA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Taxa R$</p>
                    <input type="number" min="0" step="0.01" class="form-control" name="VALOR_TAXA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Valor Líquido</p>
                    <input type="number" min="0" step="0.01" class="form-control" name="VALOR_LIQUIDO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Parcela</p>
                    <input type="text" class="form-control" name="PARCELA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Total Parc.</p>
                    <input type="text" class="form-control" name="TOTAL_PARCELAS">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Hora</p>
                    <input type="text" class="form-control" name="HORA_TRANSACAO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Estabelecimento</p>
                    <input type="text" class="form-control" name="ESTABELECIMENTO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Banco</p>
                    <input type="text" class="form-control" name="BANCO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Agência</p>
                    <input type="text" class="form-control" name="AGENCIA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Conta</p>
                    <input type="text" class="form-control" name="CONTA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Observação</p>
                    <input type="text" class="form-control" name="OBSERVACOES">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Produto</p>
                    <input type="text" class="form-control" name="PRODUTO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Meio de Captura</p>
                    <input type="text" class="form-control" name="MEIOCAPTURA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Status Conciliação</p>
                    <input type="text" class="form-control" name="STATUS_CONCILIACAO">
                  </div>
                 </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Status Financeiro</p>
                    <input type="text" class="form-control" name="STATUS_FINANCEIRO">
                  </div>
                 </th>
                 <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Justificativa</p>
                    <input type="text" class="form-control" name="JUSTIFICATIVA">
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr data-id="ID" class="">
                <td>
                  <a class="link-impressao tooltip-hint" data-title="Visualizar comprovante">
                    <i class="fas fa-print"></i>
                  </a>
                </td>
                <td data-campo="ID_ERP">-</td>
                <td data-campo="NOME_EMPRESA">-</td>
                <td data-campo="CNPJ">-</td>
                <td data-campo="DATA_VENDA" data-format="date">-</td>
                <td data-campo="DATA_PREVISAO" data-format="date">-</td>
                <td
                  class="tooltip-hint" 
                  data-image="ADQUIRENTE_IMAGEM"
                  data-default-image="assets/images/iconCart.jpeg"
                  data-text="ADQUIRENTE"
                  data-default-text="Sem identificação"
                  data-title="ADQUIRENTE"
                >
                  <div class="icon-image"></div>
                </td>
                <td
                  class="tooltip-hint" 
                  data-image="BANDEIRA_IMAGEM"
                  data-default-image="assets/images/iconCart.jpeg"
                  data-text="BANDEIRA"
                  data-default-text="Sem identificação"
                  data-title="BANDEIRA"
                >
                  <div class="icon-image"></div>
                </td>
                <td data-campo="MODALIDADE">-</td>
                <td data-campo="NSU">-</td>
                <td data-campo="AUTORIZACAO">-</td>
                <td data-campo="VALOR_BRUTO" data-format="currency">-</td>
                <td data-campo="PERCENTUAL_TAXA" data-format="decimal">-</td>
                <td class="text-danger" data-campo="VALOR_TAXA" data-format="currency"></td>
                <td data-campo="VALOR_LIQUIDO" data-format="currency">-</td>
                <td data-campo="PARCELA">-</td>
                <td data-campo="TOTAL_PARCELAS">-</td>
                <td data-campo="HORA_TRANSACAO" data-format="time">-</td>
                <td data-campo="ESTABELECIMENTO">-</td>
                <td>
                  <div class="tooltip-hint" data-title="BANCO">
                    <img data-image="BANCO_IMAGEM" data-text="BANCO" src="" alt="">
                  </div>
                </td>
                <td data-campo="AGENCIA">-</td>
                <td data-campo="CONTA">-</td>
                <td data-campo="OBSERVACOES">-</td>
                <td data-campo="PRODUTO">-</td>
                <td data-campo="MEIOCAPTURA">-</td>
                <td data-campo="STATUS_CONCILIACAO">-</td>
                <td data-campo="STATUS_FINANCEIRO">-</td>
                <td data-campo="JUSTIFICATIVA">-</td>
              </tr>      
            </tbody>
            <tfoot>
              <tr>
                <td>Totais</td>
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
                <td data-chave="TOTAL_BRUTO"></td>
                <td></td>
                <td data-chave="TOTAL_TAXA" class="text-danger"></td>
                <td data-chave="TOTAL_LIQUIDO"></td>
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
            <ul class="pagination" id="js-paginacao-operadoras">
              <li class="page-item active">
                <a href="" class="page-link">1</a>
              </li>
            </ul>
          </nav>
  
          <div class="form-group">
            <label for="js-porpagina-operadoras">Quantidade por página</label>
            <select data-vendas-tipo="operadoras" name="por_pagina" id="js-porpagina-operadoras" class="form-control">
              <option value="5" selected>5</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <option value="200">200</option>
            </select>
          </div>
        </footer>
      </div>
    </div>
  </main>

  <div id="js-loader" class="loader hidden"></div>
@endsection

@section('footerScript')
  <script defer src="{{ URL::asset('assets/js/lib/api.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/pagination.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/modal-filters.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/checker.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/vendas/vendas-operadoras.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@endsection