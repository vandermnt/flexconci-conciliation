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
            ['retorno-csv' => route('recebimentos-operadoras.retorno-csv')],
            ['retorno-recebimento' => route('recebimentos-operadoras.retorno-recebimento')],
          ]"
          :hidden-fields="[
            'bandeiras',
            'modalidades',
            'modalidades',
            'status-conciliacao',
            'status-financeiro',
            'domicilios-bancarios',
            'descricao-erp'
          ]"
          :form-data="[
            'empresas' => $empresas,
            'adquirentes' => $adquirentes,
            'estabelecimentos' => $estabelecimentos,
          ]"
        >
        <x-slot name="fields">
          <x-forms.check-group
            id="recebimento-conciliado-erp"
            :label="'Filtrar recebimentos marcados para baixa/liquidação no '.($erp->ERP ?? 'ERP').'?'"
            name="recebimento_conciliado_erp[]"
            item-value-key="value"
            item-description-key="description"
            :options="[
              ['description' => 'Sim', 'value' => 'true'],
              ['description' => 'Não', 'value' => 'false'],
            ]"
            data-group="recebimento-conciliado-erp"
            data-checker="checkbox"
            checked
          />
        </x-slot>
      </x-forms.search-form>
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
          <div class="table-description d-flex align-items-center justify-content-end">
            <h4>Recebimentos Operadoras <span id="js-quantidade-registros">(0 registros)</span></h4>
            <img src="assets/images/widgets/arrow-down.svg" alt="Vendas ERP">
          </div>
          <div class="actions d-flex align-items-center justify-content-end">
            @if(Auth::user()->USUARIO_GLOBAL === 'S')
              <button
                id="js-abrir-modal-retorno"
                class="btn button no-hover mr-1"
              >
                <i class="fas fa-undo"></i>
                Retorno Recebimento {{ $erp->ERP ?? 'ERP' }}
              </button>
            @endif
            <button id="js-retorno-csv" class="btn button no-hover mr-1 tooltip-hint" data-title="Arquivo de integração para a realização da baixa/liquidação">
                <div class="conciflex-icon icon-md">
                    <img src="assets/images/widgets/csv-file.svg" alt="CSV">
                </div>
                Retorno CSV
            </button>
            <button id="js-exportar" class="btn button no-hover">
                <div class="conciflex-icon icon-md">
                    <img src="assets/images/widgets/excel-file.svg" alt="Excel">
                </div>
                Exportar
            </button>
          </div>
        </div>

        <x-tables.tabela-recebimentos-operadoras
          id="js-tabela-recebimentos"
          class="mt-3"
          :headers="[
            'RETORNO_ERP_BAIXA' => 'Retorno Recebimento '.($erp->ERP ?? 'ERP'),
          ]"
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

  <div class="modais">
    <x-modal
      id="js-retorno-recebimento-modal"
      modal-label-id="modal-retorno-label"
      :modal-label="'Retorno Recebimento '.($erp->ERP ?? 'ERP')"
    >
      <x-slot name="content">
        <div class="form-group">
          <label for="js-data-inicial">Data Inicial:</label>
          <input id="js-data-inicial" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
        <div class="form-group">
          <label for="js-data-final">Data Final:</label>
          <input id="js-data-final" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
      </x-slot>

      <x-slot name="footer">
        <button
          id="js-cancelar-retorno-recebimento"
          type="button"
          class="btn btn-danger font-weight-bold"
        >
          Cancelar
        </button>

        <button
          id="js-retorno-recebimento"
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
  <script defer src="{{ URL::asset('assets/js/proxy/SearchFormProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/proxy/PaymentsProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/proxy/PaymentsContainerProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/recebimentos/recebimentos-operadoras.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
@endsection
