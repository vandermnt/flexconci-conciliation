@extends('layouts.analytics-master')

@section('headerStyle')
  <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{{ URL::asset('assets/css/vendas/pagina-vendas-erp.css') }}" type="text/css">
@endsection

@section('content')
  <main id="pagina-vendas-erp" class="container-fluid">
    <header>
      @component('common-components.breadcrumb')
        @slot('title') Vendas ERP @endslot
        @slot('item1') Vendas @endslot
      @endcomponent
    </header>

    <div class="card">
      <div class="card-body">
        <x-forms.search-form
          id="js-form-pesquisa"
          :urls="[
            ['erp' => route('vendas-erp.search')],
            ['filtrar-erp' => route('vendas-erp.filter')],
            ['exportar' => route('vendas-erp.export')],
          ]"
          :hidden-fields="[
            'domicilios-bancarios',
            'estabelecimentos'
          ]"
          :form-data="[
            'empresas' => $empresas,
            'adquirentes' => $adquirentes,
            'bandeiras' => $bandeiras,
            'modalidades' => $modalidades,
            'status_conciliacao' => $status_conciliacao,
            'status_financeiro' => $status_financeiro,
          ]"
        />
      </div>
    </div>

    <div class="resultados hidden">
      <div class="boxes">
        <x-box
          title="BRUTO"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_BRUTO"
          icon-path="assets/images/financeiro/bruto.svg"
          icon-description="Valor Bruto"
        />
        <x-box
          title="VALOR TAXA"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_TAXA"
          content-class="text-danger"
          icon-path="assets/images/financeiro/taxas.svg"
          icon-description="Valor Taxa"
        />
        <x-box
          title="VALOR LÍQUIDO DE VENDAS"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_LIQUIDO"
          icon-path="assets/images/financeiro/liquido.svg"
          icon-description="Valor Líquido"
        />
      </div>

      <div class="vendas">
        <div class="tabela-info d-flex align-items-center justify-content-between">
          <div class="table-description d-flex align-items-center justify-content-end">
            <h4>Vendas {{ $erp->ERP ?? 'ERP' }} <span id="js-quantidade-registros">(0 registros)</span></h4>
            <img src="assets/images/widgets/arrow-down.svg" alt="Vendas ERP">
          </div>
          <div class="acoes d-flex align-items-center justify-content-between">
            <button id="js-exportar" class="btn button no-hover">
              <i class="fas fa-file-download"></i>
              Exportar
            </button>
          </div>
        </div>

        <x-tables.tabela-vendas-erp
          id="js-tabela-erp"
          class="mt-3"
          :headers="[
            'TITULO_CAMPO1' => $erp->TITULO_CAMPO1,
            'TITULO_CAMPO2' => $erp->TITULO_CAMPO2,
            'TITULO_CAMPO3' => $erp->TITULO_CAMPO3,
            'actions' => 'Ações | Status'
          ]"
          :hidden-columns="[
            'TAXA_OPERADORA',
            'TAXA_DIFERENCA',
            'VALOR_LIQUIDO_OPERADORA',
            'DIFERENCA_LIQUIDO'
          ]"
        >
          <x-slot name="actions">
            <td class="actions-cell d-flex align-items-center justify-content-center">
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
  <script defer src="{{ URL::asset('assets/js/proxy/SalesProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/proxy/SalesContainerProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/proxy/SearchFormProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/vendas/vendas-erp.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection
