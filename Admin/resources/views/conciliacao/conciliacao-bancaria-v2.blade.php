@extends('layouts.analytics-master')

@section('headerStyle')
  <link href="{{ URL::asset('plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
  <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/globals/global.css') }}" rel="stylesheet" type="text/css"/>
  <link href="{{ URL::asset('assets/css/conciliacao/pagina-conciliacao-bancaria.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
  <main id="pagina-conciliacao-bancaria" class="container-fluid">
    <header>
      @component('common-components.breadcrumb')
        @slot('title') Conciliação Bancária @endslot
        @slot('item1') Conciliação @endslot
      @endcomponent
    </header>
    <div class="card">
      <div class="card-body">
        <form
          id="js-form-extratos"
          class="d-flex flex-column align-items-center justify-content-center"
          action=""
        >
          <div class="w-50">
            @csrf
            <h5 class="mt-0">Faça o upload dos extratos aqui:</h5>
            <input
              id="extratos"
              class="dropify"
              type="file"
              name="extratos[]"
              multiple
              accept=".ofx"
            >
            <button
              class="btn btn-lg btn-block font-weight-bold"
              type="button"
            >
              ENVIAR EXTRATOS
            </button>
          </div>
        </form>
        <div class="em-processamento">
          <h5>Conciliações em processamento</h5>
          <div class="table-responsive">
            <table class="table" id="js-tabela-processamentos">
              <thead>
                <tr>
                  <th>Data de Envio</th>
                  <th>Hora de Envio</th>
                  <th>Status</th>
                  <th>Histórico</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="card historico">
      <div class="card-body">
        <h4>Histórico</h4>
        <form id="js-form-historico" class="form search-form">
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
              <label for="adquirente">Operadora:</label>
              <input
                id="adquirente"
                class="form-control"
                type="text"
                name="adquirente"
              >
            </div>
            <button
              type="button"
              class="btn btn-sm form-button"
              data-toggle="modal"
              data-target="#adquirentes-modal"
            >
              Selecionar
            </button>
          </div>
          <div class="input-group">
            <div class="form-group flex-grow-1">
              <label for="domicilio-bancario">Domicílio Bancário:</label>
              <input
                id="domicilio-bancario"
                class="form-control"
                type="text"
                name="domicilio_bancario"
              >
            </div>
            <button
              type="button"
              class="btn btn-sm form-button"
              data-toggle="modal"
              data-target="#domicilio-bancario-modal"
            >
              Selecionar
            </button>
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
        </form>

        <div class="table-responsive mt-3">
          <table class="table table-striped" id="js-tabela-historico">
            <thead>
              <tr>
                <th>Banco</th>
                <th>Conta</th>
                <th>Operadora</th>
                <th>Data Recebimento</th>
                <th>Bruto</th>
                <th>Descontos</th>
                <th>Líquido Previsto</th>
                <th>Depositado</th>
                <th>Diferença</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modais">
      <div
        id="adquirentes-modal"
        class="modal fade"
        role="dialog"
        tabindex="-1"
        data-backdrop="static"
        data-keyboard="false"
        aria-labelledby="adquirentes-label"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <header class="modal-header d-flex align-items-center">
              <h5 class="modal-title" id="adquirentes-label">Operadoras</h5>
              <button
                class="close"
                type="button"
                data-dismiss="modal"
                data-acao="cancelar"
                data-group="adquirentes"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </header>
            <main class="modal-body">
              <div class="form-group">
                <h6>Pesquisar</h6>
                <input
                  data-filter-group="adquirentes"
                  data-filter-fields="adquirente"
                  class="form-control"
                  type="text"
                >
              </div>
              <div class="modal-checkboxes">
                <div class="d-flex align-items-center justify-content-between">
                  <p>Operadora</p>
                  <input
                      type="checkbox"
                      data-group="adquirentes"
                      data-checker="global"
                    >
                </div>
                @isset($adquirentes)
                  @foreach($adquirentes as $adquirente)
                    <div class="d-flex align-items-center justify-content-between">
                      <p>{{ $adquirente->ADQUIRENTE }}</p>
                      <input
                        type="checkbox"
                        data-filter-adquirente="{{ $adquirente->ADQUIRENTE }}"
                        data-group="adquirentes"
                        data-checker="global"
                      >
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
                data-group="adquirente"
                data-dismiss="modal"
              >
                Cancelar
              </button>

              <button
                type="button"
                class="btn btn-success font-weight-bold"
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
        id="domicilio-bancario-modal"
        class="modal fade"
        role="dialog"
        tabindex="-1"
        data-backdrop="static"
        data-keyboard="false"
        aria-labelledby="domicilio-bancario-label"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <header class="modal-header d-flex align-items-center">
              <h5 class="modal-title" id="domicilio-bancario-label">Domicílios Bancários</h5>
              <button
                class="close"
                type="button"
                data-dismiss="modal"
                data-acao="cancelar"
                data-group="domicilios-bancarios"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </header>
            <main class="modal-body">
              <div class="form-group">
                <h6>Pesquisar</h6>
                <input
                  data-filter-group="domicilios-bancarios"
                  data-filter-fields="domicilio"
                  class="form-control"
                  type="text"
                >
              </div>
              <div class="modal-checkboxes">
                <div class="d-flex align-items-center justify-content-between">
                  <p>Domicílio</p>
                  <input
                      type="checkbox"
                      data-group="domicilios-bancarios"
                      data-checker="global"
                    >
                </div>
                @isset($domicilios_bancarios)
                  @foreach($domicilios_bancarios as $domicilio)
                    <div class="d-flex align-items-center justify-content-between">
                      <p>{{ $domicilio->DOMICILIO }}</p>
                      <input
                        type="checkbox"
                        data-filter-domicilio="{{ $domicilio->DOMICILIO }}"
                        data-group="domicilios-bancarios"
                        data-checker="global"
                      >
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
                data-group="domicilios-bancarios"
                data-dismiss="modal"
              >
                Cancelar
              </button>

              <button
                type="button"
                class="btn btn-success font-weight-bold"
                data-acao="confirmar"
                data-group="domicilios-bancarios"
                data-dismiss="modal"
              >
                Confirmar
              </button>
            </footer>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@section('footerScript')
  <script src="{{ URL::asset('assets/pages/jquery.form-upload.init.js')}}"></script>
  <script src="{{ URL::asset('plugins/dropify/js/dropify.min.js')}}"></script>
@endsection