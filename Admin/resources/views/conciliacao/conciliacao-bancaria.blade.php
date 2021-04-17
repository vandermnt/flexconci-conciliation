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
            ['filtrar-operadoras' => route('conciliacao-bancaria.filter')],
            ['exportar' => route('vendas-operadoras.export')],
            ['imprimir' => route('vendas-operadoras.print', ['id' => ':id'])],
            ['desjustificar' => route('vendas-operadoras.unjustify')],
            ['retorno-csv' => route('vendas-operadoras.retorno-csv')],
						['comprovantes' => route('conciliacao-bancaria.searchComprovante')],
						['filtrar-comprovantes' => route('conciliacao-bancaria.filterComprovante')],
          ]"
          :hidden-fields="[
            'domicilios-bancarios',
            'descricao-erp',
						'bandeiras', 
            'modalidades', 
            'estabelecimentos',
						'status-conciliacao',
						'status-financeiro',
						'empresas'
          ]"
          :form-data="[
            {{-- 'empresas' => $empresas, --}}
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
                    class="tooltip-hint tooltip-left d-flex align-items-center js-show-comprovante"
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
			<x-modal
        id="js-extrato-bancario"
        modal-label-id="modal-extrato-bancario-label"
        modal-label="Envie seus extratos bancários aqui"
    	>
        <x-slot name="content">
					<div class="modal-body">
						<input
						id="teste"
						type="file"
						class="dropify"
						name="extratos[]"
						multiple
						{{-- accept=".ofx" --}}
						>
						<button type="button" class="btn btn-primary w-100 mt-2" data-dismiss="modal">Enviar</button>
					</div>
        </x-slot>

        <x-slot name="footer">
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					</div>
        </x-slot>
    </x-modal>
		<x-modal
        id="comprovante-modal"
        modal-label-id="comprovante-modal-label"
    	>
        <x-slot name="content">
					<div class="modal-body">
						<div class="tabela-info d-flex align-items-center justify-content-between flex-wrap mt-2">
							<div class="w-50 mt-auto tabela-info">
								<div class="table-description d-flex align-items-center justify-content-start w-100">
									<img class="comprovante-operadora-img" src="assets/images/widgets/cards.svg"/>
									<h4 class="text-center">Recebimentos <span id="js-quantidade-registros-comprovante">(0 registros)</span></h4>
									<img src="assets/images/widgets/arrow-down.svg" alt="">
								</div>
							</div>
							<div class="w-50 mb-auto tabela-info">
								<div class="table-description d-flex align-items-center justify-content-start w-100">
									<img class="comprovante-extrato-img" src="assets/images/conciliacao/bank.svg"/>
									<h4 class="text-center">Lançamentos Extrato Bancário <span id="js-quantidade-registros-extrato">(0 registros)</span></h4>
									<img src="assets/images/widgets/arrow-down.svg" alt="">
								</div>
							</div>
						<div class="d-flex align-items-center w-100">
							<div class="w-50 tabela-info">
								<div class="d-flex align-items-center justify-content-end mt-5 mb-2 w-100">
									<button id="js-exportar" class="btn button no-hover">
										<div class="conciflex-icon icon-md">
											<img src="assets/images/widgets/check.svg" alt="Excel">
										</div>
										Filtrar conciliadas
									</button>
									<button id="js-exportar" class="btn button no-hover ml-2">
										<div class="conciflex-icon icon-md">
											<img src="assets/images/widgets/x.svg" alt="Excel">
										</div>
										Filtrar não conciliadas
									</button>
								</div>
							</div>
							<div class="w-50 tabela-info">
								<div class="d-flex align-items-center justify-content-end mt-5 mb-2 w-100">
									<button id="js-exportar" class="btn button no-hover">
										<div class="conciflex-icon icon-md">
											<img src="assets/images/widgets/check.svg" alt="Excel">
										</div>
										Filtrar conciliadas
									</button>
									<button id="js-exportar" class="btn button no-hover ml-2">
										<div class="conciflex-icon icon-md">
											<img src="assets/images/widgets/x.svg" alt="Excel">
										</div>
										Filtrar não conciliadas
									</button>
								</div>
							</div>
						</div>
						<div class="row w-100">
							<div class="vendas col-6">
								<x-tables.tabela-conciliacao-bancaria-comprovante
									id="js-tabela-conciliacao-bancaria-comprovante"
									class="mt-2"
									:headers="[
										'actions' => 'Ações | Status',
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
											</div>
										</td>
									</x-slot>
								</x-tables.tabela-conciliacao-bancaria-comprovante>
				
								<x-tables.table-navigation
									pagination-id="js-paginacao-comprovante"
									per-page-select-id="js-por-pagina-comprovante"
									:options="['5', '10', '20', '50', '100', '200']"
								/>
							</div>
							<div class="vendas col-6">
								<x-tables.tabela-extrato-bancario
									id="js-tabela-extrato-bancario"
									class="mt-2"
									:headers="[
										'actions' => 'Ações | Status',
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
											</div>
										</td>
									</x-slot>
								</x-tables.tabela-extrato-bancario>
				
								<x-tables.table-navigation
									pagination-id="js-paginacao-extrato-bancario"
									per-page-select-id="js-por-pagina-extrato-bancario"
									:options="['5', '10', '20', '50', '100', '200']"
								/>
							</div>
						</div>
					</div>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-modal>
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
	<script defer src="{{ URL::asset('assets/js/conciliacao/comprovante/conciliacao-bancaria-comprovante.js') }}"></script>
	{{-- <script defer src="{{ URL::asset('assets/js/conciliacao/comprovante/extrato-bancario.js') }}"></script> --}}
@endsection
