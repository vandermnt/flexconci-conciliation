@extends('layouts.analytics-master')

@section('headerStyle')
  <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
  <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/globals/global.css') }}" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="{{ URL::asset('assets/css/conciliacao/pagina-conciliacao-bancaria.css') }}" type="text/css">
@endsection

@section('content')
  <main id="pagina-vendas-operadoras" class="container-fluid">
    <header>
      @component('common-components.breadcrumb')
        @slot('title') Conciliação Bancária @endslot
        @slot('item1') Conciliação @endslot
      @endcomponent
    </header>

    <div class="card">
      <div class="card-body">
        <x-forms.search-form
          id="js-form-pesquisa"
          :urls="[
						['operadoras' => route('conciliacao-bancaria.search')],
            ['filtrar-operadoras' => route('vendas-operadoras.filter')],
            ['exportar' => route('vendas-operadoras.export')],
            ['imprimir' => route('vendas-operadoras.print', ['id' => ':id'])],
            ['desjustificar' => route('vendas-operadoras.unjustify')],
            ['retorno-csv' => route('vendas-operadoras.retorno-csv')],
          ]"
          :hidden-fields="[
            'domicilios-bancarios',
            'descricao-erp',
						'bandeiras', 
            'modalidades', 
            'estabelecimentos',
						'status-conciliacao',
						'status-financeiro'
          ]"
          :form-data="[
            'empresas' => $empresas,
            'adquirentes' => $adquirentes,
          ]"
        />
      </div>
    </div>

    <div class="resultados hidden">
      <div class="boxes conciliacao-taxas">
        {{-- <x-box
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
          title="VALOR LÍQUIDO ACORDADO"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_TAXA_ACORDADA"
          icon-path="assets/images/financeiro/despesas.svg"
          icon-description="Valor Líquido"
          :dataset="[
              'hint' => 'Valor total líquido que será pago nos respectivos vencimentos pelas operadoras.'
          ]"
        />
				<x-box
          class="tooltip-hint"
          title="VALOR LÍQUIDO PRATICADO"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_TAXA"
          icon-path="assets/images/financeiro/despesas.svg"
          icon-description="Valor Taxa"
          :dataset="[
              'hint' => 'Valor total de taxas que sua empresa irá pagar quando as vendas forem liquidadas/depositadas pelas operadoras.'
          ]"
        />
				<x-box
          class="tooltip-hint"
          title="DIFERENÇA DE TAXAS"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_LIQUIDO"
          icon-path="assets/images/financeiro/alerta.svg"
          icon-description="Valor Líquido"
          :dataset="[
              'hint' => 'Valor total líquido que será pago nos respectivos vencimentos pelas operadoras.'
          ]"
        /> --}}
      </div>

      <div class="vendas">
        <div class="tabela-info d-flex align-items-center justify-content-between flex-wrap">
          <div class="table-description d-flex align-items-center justify-content-end">
            <h4>Conciliação Bancária <span id="js-quantidade-registros">(0 registros)</span></h4>
            <img src="assets/images/widgets/arrow-down.svg" alt="Vendas Operadoras">
          </div>
          <div class="d-flex align-items-center justify-content-end">
						<button id="js-exportar" class="btn button no-hover">
              <div class="conciflex-icon icon-md">
                  <img src="assets/images/widgets/excel-file.svg" alt="Excel">
              </div>
              Exportar
            </button>
          </div>
        </div>

        <x-tables.tabela-conciliacao-bancaria
					id="js-tabela-operadoras"
          class="mt-3"
          :headers="[
            'actions' => 'Ações',
          ]"
					:hiddenColumns="[
						'ID_ERP'
					]"
        >
          <x-slot name="actions">
            <td>
              <div class="actions-cell d-flex align-items-center justify-content-beetwen">
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
              </div>
            </td>
          </x-slot>
        </x-tables.tabela-conciliacao-bancaria>

        <x-tables.table-navigation
          pagination-id="js-paginacao-operadoras"
          per-page-select-id="js-por-pagina"
          :options="['10', '20', '50', '100', '200']"
        />
      </div>
    </div>
    <div class="modais">
    </div>
		<div class="modal fade modal-extrato-bancario" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Envie seus extratos bancários aqui</h5>
					</div>
					<div class="modal-body">
						<input
						id="teste"
						type="file"
						class="dropify"
						data-height="70"
						>
						<input
						id="teste"
						type="file"
						class="dropify"
						data-height="70"
						>
						<input
						id="teste"
						type="file"
						class="dropify"
						data-height="70"
						>
						<button type="button" class="btn btn-primary w-100 mt-2" data-dismiss="modal">Enviar</button>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-success">Confirmar</button>
					</div>
				</div>
			</div>
		</div>
  </main>

  <div id="js-loader" class="loader hidden"></div>
@endsection

@section('footerScript')
	<script src="{{ URL::asset('assets/pages/jquery.form-upload.init.js')}}"></script>
	<script src="{{ URL::asset('plugins/dropify/js/dropify.min.js')}}"></script>
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
  <script defer src="{{ URL::asset('assets/js/conciliacao/conciliacao-bancaria.js') }}"></script>
@endsection
