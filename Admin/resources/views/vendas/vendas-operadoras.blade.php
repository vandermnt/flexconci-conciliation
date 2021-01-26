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
        <x-forms.search-form
          id="js-form-pesquisa"
          :urls="[
            ['operadoras' => route('vendas-operadoras.index')],
            ['filtrar-operadoras' => route('vendas-operadoras.index')]
          ]"
          :form-data="[
            'empresas' => $empresas,
            'adquirentes' => $adquirentes,
            'bandeiras' => $bandeiras,
            'modalidades' => $modalidades,
            'estabelecimentos' => $estabelecimentos,
            'status_conciliacao' => $status_conciliacao,
            'status_financeiro' => $status_financeiro,
          ]"
        />
      </div>
    </div>

    <div class="resultados">
      <div class="boxes">
        <x-box 
          title="BRUTO"
          content-id="js-bruto-box"
          content="R$ 337.204,53"
          icon-path="assets/images/vendasoperadora/bruto.png"
          icon-description="Valor Bruto"
        />
        <x-box 
          title="VALOR TAXA"
          content-id="js-taxa-box"
          content="R$ -4.391,49"
          content-class="text-danger"
          icon-path="assets/images/vendasoperadora/percentagem.png"
          icon-description="Valor Taxa"
        />
        <x-box 
          title="TARIFA MÍNIMA"
          content-id="js-tarifa-box"
          content="R$ 0,00"
          content-class="text-danger"
          icon-path="assets/images/vendasoperadora/percentagem.png"
          icon-description="Tarifa Mínima"
        />
        <x-box 
          title="VALOR LÍQUIDO DE VENDAS"
          content-id="js-liquido-box"
          content="R$ 332.813,04"
          icon-path="assets/images/vendasoperadora/liquido.png"
          icon-description="Valor Líquido"
        />
      </div>

      <div class="vendas">
        <div class="tabela-info d-flex align-items-center justify-content-between">
          <h4>Vendas Operadoras <span id="js-quantidade-registros">(0 registros)</span></h4>
          <div class="acoes d-flex align-items-center justify-content-end">
            <button id="js-exportar" class="btn button no-hover">
              <i class="fas fa-file-download"></i>
              Exportar
            </button>
          </div>
        </div>

        <x-tables.tabela-vendas-operadoras
          id="js-tabela-operadoras"
          class="mt-3"
        >
          <x-slot name="actions">
            <td>
              <a class="link-impressao tooltip-hint" data-title="Visualizar comprovante">
                <i class="fas fa-print"></i>
              </a>
            </td>
          </x-slot>
        </x-tables.tabela-vendas-operadoras>

        <x-tables.table-navigation
          pagination-id="js-paginacao-operadoras"
          per-page-select-id="js-por-pagina"
          :options="['5', '10', '20', '50', '100', '200']"
        />
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
  <script defer src="{{ URL::asset('assets/js/proxy/SalesProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/vendas/vendas-operadoras.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@endsection