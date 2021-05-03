@extends('layouts.analytics-master')

@section('headerStyle')
  <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{{ URL::asset('assets/css/recebimentos/pagina-recebimentos-futuros.css') }}" type="text/css">
@endsection

@section('content')
  <main id="pagina-recebimentos-futuros" class="container-fluid">
    <header>
      @component('common-components.breadcrumb')
        @slot('title') Recebimentos Futuros @endslot
        @slot('item1') Recebimentos @endslot
      @endcomponent
    </header>

    <div class="card">
      <div class="card-body">
        <x-forms.search-form
          id="js-form-pesquisa"
          :urls="[
            ['buscar-recebimentos' => route('recebimentos-futuros.search')],
            ['filtrar-recebimentos' => route('recebimentos-futuros.filter')],
            ['exportar' => route('recebimentos-futuros.export')],
          ]"
          :hidden-fields="[
            'estabelecimentos',
            'domicilios-bancarios',
            'status-conciliacao',
            'status-financeiro',
            'descricao-erp'
          ]"
          :form-data="[
            'data_inicial' => date('Y-m-d'),
            'empresas' => $empresas,
            'adquirentes' => $adquirentes,
            'bandeiras' => $bandeiras,
            'modalidades' => $modalidades,
          ]"
        />
      </div>
    </div>

    <div class="resultados hidden">
      <div class="boxes">
        <x-box
          title="VALOR TOTAL BRUTO À RECEBER"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_BRUTO"
          icon-path="assets/images/financeiro/growth.svg"
          icon-description="Valor Bruto"
        />
        <x-box
          title="CUSTO TAXA PROJETADO"
          content="-R$ 0,00"
          content-class="text-danger"
          data-format="currency"
          data-key="TOTAL_TAXA"
          icon-path="assets/images/financeiro/accounts.svg"
          icon-description="Taxa Adm."
        />
        <x-box
          title="VALOR TOTAL LIQUIDO À RECEBER"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_LIQUIDO"
          icon-path="assets/images/financeiro/save-money.svg"
          icon-description="Valor Líquido"
        />
      </div>

      <div class="content">
        <div class="d-flex align-items-center justify-content-between">
          <div class="table-description d-flex align-items-center justify-content-end">
            <h4>Recebimentos Futuros <span id="js-quantidade-registros">(0 registros)</span></h4>
            <img src="assets/images/widgets/arrow-down.svg" alt="Vendas ERP">
          </div>
          <div class="actions d-flex flex-fill align-items-center justify-content-end">
            <x-table-config-dropdown id="js-table-config" class="mr-1" checker-group="tb-config-columns" />
            <button id="js-exportar" class="btn button no-hover">
              <div class="conciflex-icon icon-md">
                <img src="assets/images/widgets/excel-file.svg" alt="Excel">
              </div>
              Exportar
            </button>
          </div>
        </div>

        <x-tables.tabela-recebimentos-futuros
          id="js-tabela-recebimentos"
          class="mt-3"
        >
          <x-slot name="actions">
            <td></td>
          </x-slot>
        </x-tables.tabela-recebimentos-futuros>
        <x-tables.table-navigation
          pagination-id="js-paginacao-recebimentos"
          per-page-select-id="js-por-pagina"
          :options="['10', '20', '50', '100', '200']"
        />
      </div>
    </div>
  </main>

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
  <script defer src="{{ URL::asset('assets/js/proxy/SearchFormProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/proxy/PaymentsProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/proxy/PaymentsContainerProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/ui/table-section.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/ui/table-config.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/table-dragger-wrapper.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/recebimentos/recebimentos-futuros.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection
