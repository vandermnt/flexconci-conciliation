@extends('layouts.analytics-master')

@section('headerStyle')
  <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{{ URL::asset('assets/css/conciliacao/pagina-conciliacao-vendas.css') }}" type="text/css">
@endsection

@section('content')
  <main id="pagina-conciliacao-vendas" class="container-fluid">
    <header>
      @component('common-components.breadcrumb')
        @slot('title') Conciliação Automática de Vendas @endslot
        @slot('item1') Conciliação @endslot
      @endcomponent
    </header>

    <div class="card">
      <div class="card-body">
        <x-forms.search-form
          id="js-form-pesquisa"
          :urls="[
            ['buscar-erp' => route('conciliacao-vendas.buscarErp')],
            ['filtrar-erp' => route('conciliacao-vendas.filtrarErp')],
            ['buscar-operadoras' => route('conciliacao-vendas.buscarOperadoras')],
            ['filtrar-operadoras' => route('conciliacao-vendas.filtrarOperadoras')],
            ['conciliar-manualmente' => route('conciliacao-vendas.conciliarManualmente')],
            ['desconciliar-manualmente' => route('conciliacao-vendas.desconciliarManualmente')],
            ['justificar-erp' => route('vendas-erp.justify')],
            ['desjustificar-erp' => route('vendas-erp.unjustify')],
          ]"
          :hidden-fields="[
            'adquirentes',
            'bandeiras',
            'modalidades',
            'estabelecimentos',
            'domicilios-bancarios',
            'descricao-erp',
            'status-financeiro',
          ]"
          :form-data="[
            'empresas' => $empresas,
            'status_conciliacao' => $status_conciliacao,
          ]"
        />
      </div>
    </div>

    <div class="resultados hidden">
      <div class="boxes">
        <x-box
          :title="'VENDAS '.($erp->ERP ? mb_strtoupper($erp->ERP, 'utf-8') : 'SISTEMA')"
          content="R$ 18.434,51"
          data-format="currency"
          data-key="TOTAL_BRUTO"
          icon-path="assets/images/widgets/notebook.svg"
          icon-description="Vendas ERP"
        />
        <x-box
          title="CONCILIADO"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_CONCILIADO"
          icon-path="assets/images/widgets/check.svg"
          icon-description="Conciliado"
        />
        <x-box
          title="DIVERGENTE"
          content="R$ 16.518,46"
          data-format="currency"
          data-key="TOTAL_DIVERGENTE"
          icon-path="assets/images/widgets/x.svg"
          icon-description="Divergente"
        />
        <x-box
          title="CONC. MANUAL"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_CONCILIADO_MANUAL"
          icon-path="assets/images/widgets/handshake.svg"
          icon-description="Conciliacao Manual"
        />
        <x-box
          title="JUSTIFICADO"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_JUSTIFICADO"
          icon-path="assets/images/widgets/flag.svg"
          icon-description="Justificado"
        />
        <x-box
          title="PENDÊNCIAS ERP"
          content="R$ 1.916,05"
          data-format="currency"
          data-key="TOTAL_NAO_CONCILIADO"
          icon-path="assets/images/widgets/exclamation-mark.svg"
          icon-description="Pendências"
        />
        <x-box
          title="PENDÊNCIAS OPER."
          content="R$ 39.716,97"
          data-format="currency"
          data-key="TOTAL_PENDENCIAS_OPERADORAS"
          icon-path="assets/images/widgets/exclamation-mark.svg"
          icon-description="Pendências"
        />
      </div>

      <div class="vendas">
        <div class="tabela-info d-flex align-items-center justify-content-between">
          <div class="table-description d-flex align-items-center justify-content-end">
            <h4>Vendas {{ $erp->ERP ?? 'ERP' }} <span id="js-quantidade-registros-erp">(0 registros)</span></h4>
            <img src="assets/images/widgets/arrow-down.svg" alt="Vendas ERP">
          </div>
          <div class="acoes d-flex align-items-center justify-content-between">
            <button id="js-conciliar" class="btn mr-1 button no-hover">
              <i class="far fa-handshake"></i>
              Conciliar
            </button>
            <button id="js-desconciliar" class="btn mr-1 button no-hover">
              <i class="fas fa-handshake-slash"></i>
              Desconciliar
            </button>
            <button
              id="js-justificar-erp"
              class="btn mr-1 button no-hover"
            >
              <i class="far fa-flag"></i>
              Justificar
            </button>
            <button id="js-desjustificar-erp" class="btn mr-1 button no-hover">
              <i class="fas fa-comment-slash"></i>
              Desjustificar
            </button>
            <button id="js-exportar-erp" class="btn button no-hover">
              <i class="fas fa-file-download"></i>
              Exportar
            </button>
          </div>
        </div>

        <x-tables.tabela-vendas-erp
          id="js-tabela-erp"
          class="mt-3"
          :headers="[
            'TAXA' => $erp->ERP ? 'Taxa '.$erp->ERP.' %' : null,
            'VALOR_LIQUIDO' => $erp->ERP ? 'Valor Líquido '.$erp->ERP : null,
            'TITULO_CAMPO1' => $erp->TITULO_CAMPO1,
            'TITULO_CAMPO2' => $erp->TITULO_CAMPO2,
            'TITULO_CAMPO3' => $erp->TITULO_CAMPO3,
            'actions' => 'Ações | Status'
          ]"
          :hidden-columns="[
            'TID',
            'CARTAO',
            'HORA',
            'ESTABELECIMENTO'
          ]"
        >
          <x-slot name="actions">
            <td class="actions-cell d-flex align-items-center justify-content-between">
              <input
                name="id_erp[]"
                type="checkbox"
                data-value-key="ID_ERP"
              >
              <div class="tooltip-hint d-flex align-items-center" data-default-title="Visualizar Detalhes">
                <i class="fas fa-eye"></i>
              </div>
              <div class="tooltip-hint" data-title="STATUS_CONCILIACAO">
                <img data-image="STATUS_CONCILIACAO_IMAGEM">
              </div>
            </td>
          </x-slot>
        </x-tables.tabela-vendas-erp>

        <x-tables.table-navigation
          pagination-id="js-paginacao-erp"
          per-page-select-id="js-por-pagina-erp"
          :options="['5', '10', '20', '50', '100', '200']"
        />
      </div>

      <div class="vendas">
        <div class="tabela-info d-flex align-items-center justify-content-between">
          <div class="table-description d-flex align-items-center justify-content-end">
            <h4>Vendas Operadoras <span id="js-quantidade-registros-operadoras">(0 registros)</span></h4>
            <img src="assets/images/widgets/arrow-down.svg" alt="Vendas Operadoras">
          </div>
          <div class="d-flex align-items-center justify-content-end">
            <button
              id="js-justificar-operadora"
              class="btn mr-1 button no-hover"
            >
              <i class="far fa-flag"></i>
              Justificar
            </button>
            <button id="js-exportar-operadoras" class="btn button no-hover">
              <i class="fas fa-file-download"></i>
              Exportar
            </button>
          </div>
        </div>

        <x-tables.tabela-vendas-operadoras
          id="js-tabela-operadoras"
          class="mt-3"
          :headers="[
            'actions' => 'Ações | Status',
          ]"
          :hidden-columns="[
            'DIVERGENCIA'
          ]"
        >
          <x-slot name="actions">
            <td class="actions-cell d-flex align-items-center justify-content-between">
              <input
                name="id_operadoras[]"
                type="checkbox"
                data-value-key="ID"
              >
              <div class="tooltip-hint d-flex align-items-center" data-default-title="Visualizar Detalhes">
                <i class="fas fa-eye"></i>
              </div>
              <div class="tooltip-hint" data-title="STATUS_CONCILIACAO">
                <img data-image="STATUS_CONCILIACAO_IMAGEM">
              </div>
            </td>
          </x-slot>
        </x-tables.tabela-vendas-operadoras>

        <x-tables.table-navigation
          pagination-id="js-paginacao-operadoras"
          per-page-select-id="js-por-pagina-operadoras"
          :options="['5', '10', '20', '50', '100', '200']"
        />
      </div>
    </div>
  </main>

  <div class="modais">
    <x-modal
      id="js-justificar-modal"
      modal-label="Justificar"
      modal-label-id="justificar-label"
    >
      <x-slot name="content">
        <form id="js-justificar-form" action="">
          <h6>Justificativa</h6>
          <select
            id="justificativa"
            name="justificativa"
            class="form-control"
          >
            <option value="" selected disabled>Selecione uma justificativa</option>
            @foreach (($justificativas ?? []) as $justificativa)
              <option value="{{ $justificativa->CODIGO }}">{{ $justificativa->JUSTIFICATIVA }}</option>
            @endforeach
          </select>
        </form>
      </x-slot>
      <x-slot name="footer">
        <button
          type="button"
          class="btn btn-danger font-weight-bold"
          data-dismiss="modal"
        >
          Cancelar
        </button>
        <button
          id="js-justificar"
          type="button"
          class="btn btn-success font-weight-bold"
        >
          Confirmar
        </button>
      </x-slot>
    </x-modal>
  </div>

  <div id="js-loader" class="loader hidden"></div>
@endsection

@section('footerScript')
  <script defer src="{{ URL::asset('assets/js/lib/api.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/formatter.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/pagination.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/modal-filters.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/checker.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/ui/table-render.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/ui/box.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/ui/index.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/proxy/SalesProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/proxy/SalesContainerProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/proxy/SearchFormProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/conciliacao/conciliacao-vendas.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection