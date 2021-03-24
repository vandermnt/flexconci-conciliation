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
        @slot('title') Conciliação de Taxas @endslot
        @slot('item1') Conciliação @endslot
      @endcomponent
    </header>

    <div class="card">
      <div class="card-body">
        <x-forms.search-form
          id="js-form-pesquisa"
          :urls="[
            ['operadoras' => route('vendas-operadoras.search')],
            ['filtrar-operadoras' => route('vendas-operadoras.filter')],
            ['exportar' => route('vendas-operadoras.export')],
            ['imprimir' => route('vendas-operadoras.print', ['id' => ':id'])],
            ['desjustificar' => route('vendas-operadoras.unjustify')],
            ['retorno-csv' => route('vendas-operadoras.retorno-csv')],
          ]"
          :hidden-fields="[
            'domicilios-bancarios',
            'descricao-erp',
						'status-conciliacao',
						'status-financeiro'
          ]"
          :form-data="[
            'empresas' => $empresas,
            'adquirentes' => $adquirentes,
            'bandeiras' => $bandeiras,
            'modalidades' => $modalidades,
            'estabelecimentos' => $estabelecimentos,
          ]"
        />
      </div>
    </div>

    <div class="resultados hidden">
      <div class="boxes">
        <x-box
          class="tooltip-hint"
          title="VALOR TOTAL BRUTO"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_BRUTO"
          icon-path="assets/images/financeiro/bruto.svg"
          icon-description="Valor Bruto"
          :dataset="[
              'hint' => 'Valor total bruto vendido nas operadoras.'
          ]"
        />
        <x-box
          class="tooltip-hint"
          title="CUSTO TAXA"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_TAXA"
          content-class="text-danger"
          icon-path="assets/images/financeiro/despesas.svg"
          icon-description="Valor Taxa"
          :dataset="[
              'hint' => 'Valor total de taxas que sua empresa irá pagar quando as vendas forem liquidadas/depositadas pelas operadoras.'
          ]"
        />
        <x-box
          class="tooltip-hint"
          title="VALOR TOTAL LÍQUIDO"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_LIQUIDO"
          icon-path="assets/images/financeiro/liquido.svg"
          icon-description="Valor Líquido"
          :dataset="[
              'hint' => 'Valor total líquido que será pago nos respectivos vencimentos pelas operadoras.'
          ]"
        />
				<x-box
          class="tooltip-hint"
          title="CUSTO TAXA ACORDADO"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_LIQUIDO"
          icon-path="assets/images/financeiro/liquido.svg"
          icon-description="Valor Líquido"
          :dataset="[
              'hint' => 'Valor total líquido que será pago nos respectivos vencimentos pelas operadoras.'
          ]"
        />
				<x-box
          class="tooltip-hint"
          title="CUSTO TAXA PRATICADO"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_LIQUIDO"
          icon-path="assets/images/financeiro/liquido.svg"
          icon-description="Valor Líquido"
          :dataset="[
              'hint' => 'Valor total líquido que será pago nos respectivos vencimentos pelas operadoras.'
          ]"
        />
				<x-box
          class="tooltip-hint"
          title="DIFERENÇA DE TAXAS"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_LIQUIDO"
          icon-path="assets/images/financeiro/liquido.svg"
          icon-description="Valor Líquido"
          :dataset="[
              'hint' => 'Valor total líquido que será pago nos respectivos vencimentos pelas operadoras.'
          ]"
        />
      </div>

      <div class="vendas">
        <div class="tabela-info d-flex align-items-center justify-content-between">
          <div class="table-description d-flex align-items-center justify-content-end">
            <h4>Vendas Operadoras <span id="js-quantidade-registros">(0 registros)</span></h4>
            <img src="assets/images/widgets/arrow-down.svg" alt="Vendas Operadoras">
          </div>
          <div class="d-flex align-items-center justify-content-end">
            <button id="js-desjustificar" class="btn button no-hover mr-1">
              <i class="fas fa-comment-slash"></i>
              Desjustificar
            </button>
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

        <x-tables.tabela-conciliacao-taxas
          id="js-tabela-operadoras"
          class="mt-3"
          :headers="[
            'actions' => 'Ações | Status',
          ]"
        >
          <x-slot name="actions">
            <td>
              <div class="actions-cell d-flex align-items-center justify-content-between">
                <input
                    class="mr-2"
                    name="id_operadora"
                    type="checkbox"
                    data-value-key="ID"
                >
                <div
                    class="tooltip-hint tooltip-left d-flex align-items-center js-show-details"
                    data-default-title="Visualizar Detalhes"
                    data-toggle="modal"
                    data-target="#comprovante-modal"
                >
                    <i class="fas fa-eye"></i>
                </div>
                <div class="tooltip-hint tooltip-left ml-2" data-title="STATUS_CONCILIACAO">
                    <img data-image="STATUS_CONCILIACAO_IMAGEM">
                </div>
              </div>
            </td>
          </x-slot>
        </x-tables.tabela-conciliacao-taxas>

        <x-tables.table-navigation
          pagination-id="js-paginacao-operadoras"
          per-page-select-id="js-por-pagina"
          :options="['10', '20', '50', '100', '200']"
        />
      </div>
    </div>
    <div class="modais">
        <x-modal
          id="comprovante-modal"
          modal-label-id="comprovante"
          modal-label="Comprovante"
        >
          <x-slot name="content">
            <div class="comprovante">
              <div class="header">
                <h4 class="font-weight-bold">
                    <span data-key="NOME_EMPRESA"></span>
                </h4>
                <h6>
                    CNPJ: <span data-key="CNPJ"></span>
                </h6>
              </div>
              <hr>
              <div class="body">
                <h6>
                    DATA VENDA: <span data-key="DATA_VENDA" data-format="date"></span>
                </h6>
                <h6>
                    OPERADORA: <span data-key="ADQUIRENTE"></span>
                </h6>
                <h6>
                    BANDEIRA: <span data-key="BANDEIRA"></span>
                </h6>
                <h6>
                    FORMA DE PAGAMENTO: <span data-key="MODALIDADE"></span>
                </h6>
                <h6>
                    ESTABELECIMENTO: <span data-key="ESTABELECIMENTO"></span>
                </h6>
                <h6>
                    CARTAO: <span data-key="CARTAO"></span>
                </h6>
                <h6 class="font-weight-bold">
                    VALOR: <span data-key="VALOR_BRUTO" data-format="currency"></span>
                </h6>
                <h6>
                    DATA PREVISÃO: <span data-key="DATA_PREVISAO" data-format="date"></span>
                </h6>
              </div>
            </div>
          </x-slot>

          <x-slot name="footer">
            <button
            type="button"
            class="btn btn-danger font-weight-bold"
            data-action="close"
            data-dismiss="modal"
          >
            Fechar
          </button>

          <button
            type="button"
            class="btn btn-success font-weight-bold"
            data-action="print"
            data-dismiss="modal"
          >
            Imprimir
          </button>
        </x-slot>
      </x-modal>
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
  <script defer src="{{ URL::asset('assets/js/vendas/vendas-operadoras.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection
