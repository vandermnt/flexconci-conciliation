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
          :urls="[]"
          :hidden-fields="[
            'bandeiras',
            'modalidades',
            'estabelecimentos',
            'modalidades',
            'status-conciliacao',
            'status-financeiro',
          ]"
          :form-data="[
            'empresas' => [],
            'adquirentes' => [],
            'domicilios_bancarios' => [],
          ]"
        />
      </div>
    </div>

    <div class="resultados">
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
          data-key=""
          icon-path="assets/images/financeiro/pagamentos.svg"
          icon-description="Pag. Normal"
        />
        <x-box
          title="PAG. ANTECIPADO"
          content="R$ 0,00"
          data-format="currency"
          data-key=""
          icon-path="assets/images/financeiro/pagamentos.svg"
          icon-description="Pag. Antecipado"
        />
        <x-box
          title="PAG. AVULSO"
          content="R$ 0,00"
          data-format="currency"
          data-key=""
          icon-path="assets/images/financeiro/pagamentos.svg"
          icon-description="Pag. Avulso"
        />
        <x-box
          title="TAXA ADM."
          content="-R$ 0,00"
          content-class="text-danger"
          data-format="currency"
          data-key=""
          icon-path="assets/images/financeiro/taxas.svg"
          icon-description="Taxa Adm."
        />
        <x-box
          title="ANTECIPAÇÃO"
          content="-R$ 0,00"
          content-class="text-danger"
          data-format="currency"
          data-key=""
          icon-path="assets/images/financeiro/taxas.svg"
          icon-description="Antecipação"
        />
        <x-box
          title="OUTRAS DESPESAS"
          content="R$ 0,00"
          data-format="currency"
          data-key=""
          icon-path="assets/images/financeiro/despesas.svg"
          icon-description="Outras Despesas"
        />
        <x-box
          title="LÍQUIDO"
          content="R$ 0,00"
          data-format="currency"
          data-key=""
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
        />
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