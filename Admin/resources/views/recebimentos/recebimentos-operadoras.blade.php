@extends('layouts.analytics-master')

@section('headerStyle')
  <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{{ URL::asset('assets/css/recebimentos/pagina-recebimentos-operadoras.css') }}" type="text/css">
@endsection

@section('content')
  <main id="pagina-recebimentos-operadoras" class="container-fluid">
    <header>
      @component('common-components.breadcrumb')
        @slot('title') Recebimentos Operadoras @endslot
        @slot('item1') Recebimentos @endslot
      @endcomponent
    </header>

    <div class="card">
      <div class="card-body">
        <x-forms.search-form
          id="js-form-pesquisa"
          :urls="[
            ['buscar-recebimentos' => route('recebimentos-operadoras.search')],
            ['filtrar-recebimentos' => route('recebimentos-operadoras.filter')],
            ['exportar' => route('recebimentos-operadoras.export')],
          ]"
          :hidden-fields="[
            'bandeiras',
            'modalidades',
            'estabelecimentos',
            'modalidades',
            'status-conciliacao',
            'status-financeiro',
            'descricao-erp'
          ]"
          :form-data="[
            'empresas' => $empresas,
            'adquirentes' => $adquirentes,
            'domicilios_bancarios' => $domicilios_bancarios,
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
          title="PAG. NORMAL"
          content="R$ 0,00"
          data-format="currency"
          data-key="PAG_NORMAL"
          icon-path="assets/images/financeiro/pagamentos.svg"
          icon-description="Pag. Normal"
        />
        <x-box
          title="PAG. ANTECIPADO"
          content="R$ 0,00"
          data-format="currency"
          data-key="PAG_ANTECIPADO"
          icon-path="assets/images/financeiro/pag-antecipado.svg"
          icon-description="Pag. Antecipado"
        />
        <x-box
          title="PAG. AVULSO"
          content="R$ 0,00"
          data-format="currency"
          data-key="PAG_AVULSO"
          icon-path="assets/images/financeiro/pagamentos.svg"
          icon-description="Pag. Avulso"
        />
        <x-box
          title="TAXA ADM."
          content="-R$ 0,00"
          content-class="text-danger"
          data-format="currency"
          data-key="TOTAL_TAXA"
          icon-path="assets/images/financeiro/taxa-adm.svg"
          icon-description="Taxa Adm."
        />
        <x-box
          title="CUSTO ANTECIPAÇÃO"
          content="-R$ 0,00"
          content-class="text-danger"
          data-format="currency"
          data-key="TOTAL_ANTECIPACAO"
          icon-path="assets/images/financeiro/taxas.svg"
          icon-description="Antecipação"
        />
        <x-box
          title="OUTRAS DESPESAS"
          content="-R$ 0,00"
          content-class="text-danger"
          data-format="currency"
          data-key="TOTAL_DESPESAS"
          icon-path="assets/images/financeiro/despesas.svg"
          icon-description="Outras Despesas"
        />
        <x-box
          title="LÍQUIDO"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_LIQUIDO"
          icon-path="assets/images/financeiro/liquido.svg"
          icon-description="Valor Líquido"
        />
      </div>

      <div class="content">
        <div class="d-flex align-items-center justify-content-between">
          <h4>Recebimentos Operadoras <span id="js-quantidade-registros">(0 registros)</span></h4>
          <div class="actions d-flex align-items-center justify-content-end">
            <button id="js-exportar" class="btn button no-hover">
              <i class="fas fa-file-download"></i>
              Exportar
            </button>
          </div>
        </div>

        <x-tables.tabela-recebimentos-operadoras
          id="js-tabela-recebimentos"
          class="mt-3"
        >
          <x-slot name="actions">
            <td></td>
          </x-slot>
        </x-tables.tabela-recebimentos-operadoras>
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
  <script defer src="{{ URL::asset('assets/js/recebimentos/recebimentos-operadoras.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection